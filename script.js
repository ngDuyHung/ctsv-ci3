import http from 'k6/http';
import { check, group, sleep } from 'k6';
import { SharedArray } from 'k6/data';
import { textSummary } from 'https://jslib.k6.io/k6-summary/0.0.2/index.js';

// === CẤU HÌNH ===
const BASE_URL       = __ENV.BASE_URL       || 'http://ctsv-ci3.test:8080';
//const LOGIN_PASSWORD = __ENV.LOGIN_PASSWORD  || 'DH52200731'; //

//Tai khoan admin tk DH52200731 PASS DH52200731

// Danh sách tài khoản sinh viên — mỗi VU dùng 1 tài khoản riêng
// để tránh tranh chấp session trên DB (session lock cùng user).
const USERS = new SharedArray('users', function () {
    // 60 tài khoản khác nhau, đủ cho 500+ VUs (round-robin)
    return [
        'DH52200296', 'DH52200297', 'DH52200299', 'DH52200300', 'DH52200301',
        'DH52200302', 'DH52200303', 'DH52200305', 'DH52200306', 'DH52200307',
        'DH52200308', 'DH52200310', 'DH52200311', 'DH52200312', 'DH52200313',
        'DH52200314', 'DH52200315', 'DH52200316', 'DH52200317', 'DH52200318',
        'DH52200319', 'DH52200320', 'DH52200321', 'DH52200322', 'DH52200323',
        'DH52200324', 'DH52200325', 'DH52200326', 'DH52200327', 'DH52200329',
        'DH52200330', 'DH52200331', 'DH52200332', 'DH52200334', 'DH52200335',
        'DH52200336', 'DH52200337', 'DH52200338', 'DH52200339', 'DH52200341',
        'DH52200342', 'DH52200343', 'DH52200344', 'DH52200345', 'DH52200346',
        'DH52200347', 'DH52200349', 'DH52200350', 'DH52200352', 'DH52200353',
        'DH52200354', 'DH52200355', 'DH52200356', 'DH52200357', 'DH52200358',
        'DH52200359', 'DH52200360', 'DH52200361', 'DH52200362', 'DH52200363',
    ];
});

export const options = {
    stages: [
        { duration: '30s', target: 50 },   // Warm-up
        { duration: '1m',  target: 300 },  // Ramp lên 300
        { duration: '30s', target: 500 },  // Ramp lên 500
        { duration: '20s', target: 0 },    // Ramp-down
    ],
    thresholds: {
        'http_req_failed{type:page}': ['rate<0.05'],
        'http_req_duration{type:page}': ['p(95)<3000'],
    },
};

// Helper: Đăng nhập và trả về true nếu thành công
function doLogin() {
    let email = USERS[__VU % USERS.length];
    let res = http.post(
        `${BASE_URL}/login/verifylogin`,
        { email: email, password: email },
        { tags: { type: 'auth' }, redirects: 0 }
    );
    // 302 redirect = login thành công (cookie session đã set)
    return res.status === 302;
}

// Helper: GET page — không retry, đo thuần performance
function getPage(url, tags) {
    return http.get(url, { tags: tags });
}

export function setup() {
    let res = http.get(`${BASE_URL}/login`);
    if (res.status !== 200) {
        console.error(`Server không phản hồi tại ${BASE_URL}. Status: ${res.status}`);
    }
    return {};
}

export default function () {
    // Login mỗi iteration để đảm bảo session luôn valid
    doLogin();

    // --- Notification page ---
    group('01_User_Home_Notification', function () {
        let res = getPage(`${BASE_URL}/notification`, { type: 'page' });
        check(res, {
            'notification status 200': (r) => r.status === 200,
            'has welcome text': (r) => r.body && r.body.includes('Thông báo'),
        });
    });
    sleep(1);

    // --- Quiz list ---
    group('02_Access_Quiz_List', function () {
        let res = getPage(`${BASE_URL}/quiz`, { type: 'page' });
        check(res, {
            'quiz status 200': (r) => r.status === 200,
            'verify quiz presence': (r) => r.body && r.body.includes('Bài thi'),
        });
    });
    sleep(1);

    // --- Quiz detail ---
    group('03_View_Quiz_Detail', function () {
        let res = getPage(`${BASE_URL}/quiz/quiz_detail/61`, { type: 'page' });
        check(res, {
            'detail status 200': (r) => r.status === 200,
            'detail loaded': (r) => r.body && r.body.includes('Thông tin bài thi'),
        });
    });
    sleep(1);
}

// Gửi kết quả test lên dashboard sau khi k6 hoàn thành
export function handleSummary(data) {
    // Trích xuất chỉ các metrics cần thiết
    var summary = {
        timestamp: new Date().toISOString(),
        // Thông tin test
        vus_max: data.metrics.vus_max ? data.metrics.vus_max.values.max : 0,
        duration: data.state ? data.state.testRunDurationMs : 0,
        iterations: data.metrics.iterations ? data.metrics.iterations.values.count : 0,
        // HTTP metrics
        http_reqs: data.metrics.http_reqs ? data.metrics.http_reqs.values.count : 0,
        http_reqs_rate: data.metrics.http_reqs ? data.metrics.http_reqs.values.rate : 0,
        http_req_duration_avg: data.metrics['http_req_duration'] ? data.metrics['http_req_duration'].values.avg : 0,
        http_req_duration_med: data.metrics['http_req_duration'] ? data.metrics['http_req_duration'].values.med : 0,
        http_req_duration_p90: data.metrics['http_req_duration'] ? data.metrics['http_req_duration'].values['p(90)'] : 0,
        http_req_duration_p95: data.metrics['http_req_duration'] ? data.metrics['http_req_duration'].values['p(95)'] : 0,
        http_req_duration_max: data.metrics['http_req_duration'] ? data.metrics['http_req_duration'].values.max : 0,
        http_req_duration_min: data.metrics['http_req_duration'] ? data.metrics['http_req_duration'].values.min : 0,
        http_req_failed_rate: data.metrics.http_req_failed ? data.metrics.http_req_failed.values.rate : 0,
        // Checks
        checks_total: data.metrics.checks ? data.metrics.checks.values.passes + data.metrics.checks.values.fails : 0,
        checks_passed: data.metrics.checks ? data.metrics.checks.values.passes : 0,
        checks_failed: data.metrics.checks ? data.metrics.checks.values.fails : 0,
        // Iteration duration
        iter_duration_avg: data.metrics.iteration_duration ? data.metrics.iteration_duration.values.avg : 0,
        iter_duration_p95: data.metrics.iteration_duration ? data.metrics.iteration_duration.values['p(95)'] : 0,
        // Network
        data_received: data.metrics.data_received ? data.metrics.data_received.values.count : 0,
        data_sent: data.metrics.data_sent ? data.metrics.data_sent.values.count : 0,
        // Thresholds
        thresholds_passed: !data.metrics['http_req_failed{type:page}'] || data.metrics['http_req_failed{type:page}'].thresholds ? true : false,
    };

    // POST kết quả lên server
    var postRes = http.post(
        `${BASE_URL}/dashboard/save_k6_result`,
        JSON.stringify(summary),
        { headers: { 'Content-Type': 'application/json' } }
    );

    return {
        stdout: textSummary(data, { indent: ' ', enableColors: true }),
    };
}

