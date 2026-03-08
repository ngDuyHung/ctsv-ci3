<div class="container">



  <div id="update_notice"></div>

  <!-- Real-time Active Sessions Monitor -->
  <?php $logged_in = $this->session->userdata('logged_in');
  if (isset($logged_in['su']) && $logged_in['su'] == '1'): ?>
    <style>
      #rt-monitor {
        margin-bottom: 18px
      }

      #rt-monitor * {
        box-sizing: border-box
      }

      .rt-hdr {
        background: linear-gradient(135deg, #1e3c72, #2a5298);
        color: #fff;
        border-radius: 8px 8px 0 0;
        padding: 10px 16px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap
      }

      .rt-hdr h4 {
        margin: 0;
        font-size: 16px;
        font-weight: 600
      }

      .rt-hdr-right {
        display: flex;
        align-items: center;
        gap: 12px;
        font-size: 12px
      }

      .rt-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: #2ecc71;
        display: inline-block;
        margin-right: 5px;
        animation: rtb 1.5s infinite
      }

      @keyframes rtb {

        0%,
        100% {
          opacity: 1
        }

        50% {
          opacity: .25
        }
      }

      .rt-body {
        background: #f7f8fc;
        border: 1px solid #dde;
        border-top: 0;
        border-radius: 0 0 8px 8px;
        padding: 14px
      }

      .rt-cards {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        margin-bottom: 12px
      }

      .rt-card {
        flex: 1;
        min-width: 120px;
        background: #fff;
        border-radius: 8px;
        padding: 12px 8px;
        text-align: center;
        border: 1px solid #e8e8e8;
        transition: box-shadow .2s
      }

      .rt-card:hover {
        box-shadow: 0 2px 8px rgba(0, 0, 0, .08)
      }

      .rt-card .rt-val {
        font-size: 32px;
        font-weight: 700;
        line-height: 1.1;
        margin: 4px 0 2px
      }

      .rt-card .rt-lbl {
        font-size: 11px;
        color: #888;
        line-height: 1.3
      }

      .rt-card .rt-sub {
        font-size: 10px;
        color: #aaa;
        margin-top: 2px
      }

      .rt-card .rt-ico {
        font-size: 20px;
        margin-bottom: 2px
      }

      .rt-chart-wrap {
        background: #fff;
        border-radius: 8px;
        padding: 12px;
        border: 1px solid #e8e8e8
      }

      .rt-chart-title {
        font-size: 12px;
        font-weight: 600;
        color: #555;
        margin-bottom: 6px
      }

      .rt-log {
        background: #fff;
        border-radius: 8px;
        padding: 10px 12px;
        border: 1px solid #e8e8e8;
        margin-top: 10px;
        max-height: 120px;
        overflow-y: auto;
        font-family: Consolas, monospace;
        font-size: 11px;
        color: #555
      }

      .rt-log div {
        padding: 1px 0;
        border-bottom: 1px solid #f5f5f5
      }

      .rt-log .up {
        color: #27ae60
      }

      .rt-log .down {
        color: #e74c3c
      }

      .rt-log .same {
        color: #95a5a6
      }

      .rt-btn {
        background: #fff;
        border: 1px solid #ccc;
        border-radius: 4px;
        padding: 4px 12px;
        font-size: 11px;
        cursor: pointer;
        color: #555
      }

      .rt-btn:hover {
        background: #f0f0f0
      }

      .rt-btn-danger {
        border-color: #e74c3c;
        color: #e74c3c
      }

      .rt-btn-danger:hover {
        background: #fdf0f0
      }

      .rt-summary {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        margin-top: 10px
      }

      .rt-tag {
        display: inline-block;
        padding: 3px 10px;
        border-radius: 12px;
        font-size: 11px;
        font-weight: 500
      }

      /* --- System Health Panel --- */
      .rt-health {
        display: flex;
        gap: 12px;
        margin-bottom: 12px;
        flex-wrap: wrap
      }

      .rt-health-main {
        flex: 0 0 280px;
        border-radius: 10px;
        padding: 16px 18px;
        text-align: center;
        transition: all .4s ease;
        position: relative;
        overflow: hidden
      }

      .rt-health-main::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: inherit;
        opacity: .12;
        z-index: 0
      }

      .rt-health-main>* {
        position: relative;
        z-index: 1
      }

      .rt-health-icon {
        font-size: 48px;
        margin-bottom: 6px
      }

      .rt-health-label {
        font-size: 20px;
        font-weight: 700;
        margin-bottom: 2px
      }

      .rt-health-desc {
        font-size: 12px;
        line-height: 1.5;
        opacity: .9
      }

      .rt-health-details {
        flex: 1;
        min-width: 260px;
        background: #fff;
        border-radius: 10px;
        border: 1px solid #e8e8e8;
        padding: 14px 16px
      }

      .rt-health-details h5 {
        margin: 0 0 10px;
        font-size: 13px;
        font-weight: 600;
        color: #555
      }

      .rt-hd-row {
        display: flex;
        align-items: center;
        margin-bottom: 10px;
        font-size: 12px
      }

      .rt-hd-row:last-child {
        margin-bottom: 0
      }

      .rt-hd-icon {
        width: 28px;
        height: 28px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 13px;
        margin-right: 10px;
        flex-shrink: 0
      }

      .rt-hd-info {
        flex: 1
      }

      .rt-hd-title {
        font-weight: 600;
        color: #333;
        margin-bottom: 1px
      }

      .rt-hd-value {
        color: #888;
        font-size: 11px
      }

      .rt-hd-bar {
        width: 80px;
        height: 6px;
        background: #f0f0f0;
        border-radius: 3px;
        overflow: hidden;
        margin-left: 10px;
        flex-shrink: 0
      }

      .rt-hd-bar-fill {
        height: 100%;
        border-radius: 3px;
        transition: width .4s ease, background .4s ease
      }

      /* Health pulse animation */
      @keyframes healthPulse {
        0% {
          box-shadow: 0 0 0 0 rgba(46, 204, 113, .4)
        }

        70% {
          box-shadow: 0 0 0 12px rgba(46, 204, 113, 0)
        }

        100% {
          box-shadow: 0 0 0 0 rgba(46, 204, 113, 0)
        }
      }

      @keyframes healthWarn {
        0% {
          box-shadow: 0 0 0 0 rgba(243, 156, 18, .4)
        }

        70% {
          box-shadow: 0 0 0 12px rgba(243, 156, 18, 0)
        }

        100% {
          box-shadow: 0 0 0 0 rgba(243, 156, 18, 0)
        }
      }

      @keyframes healthCrit {
        0% {
          box-shadow: 0 0 0 0 rgba(231, 76, 60, .4)
        }

        70% {
          box-shadow: 0 0 0 12px rgba(231, 76, 60, 0)
        }

        100% {
          box-shadow: 0 0 0 0 rgba(231, 76, 60, 0)
        }
      }
    </style>
    <div id="rt-monitor">
      <div class="rt-hdr">
        <h4><i class="fa fa-signal"></i>&nbsp; GIÁM SÁT TẢI REAL-TIME</h4>
        <div class="rt-hdr-right">
          <span><span class="rt-dot" id="rt-dot"></span><span id="rt-status">Đang kết nối...</span></span>
          <span>Cập nhật: <strong id="rt-time">--:--:--</strong></span>
          <button class="rt-btn rt-btn-danger" id="rt-reset" title="Xóa toàn bộ dữ liệu, bắt đầu lại từ 0"><i class="fa fa-refresh"></i> Reset</button>
        </div>
      </div>
      <div class="rt-body">
        <!-- Row 1: Cards chỉ số chính -->
        <div class="rt-cards">
          <div class="rt-card">
            <div class="rt-ico" style="color:#e74c3c"><i class="fa fa-users"></i></div>
            <div class="rt-val" style="color:#e74c3c" id="v-concurrent">0</div>
            <div class="rt-lbl">Người dùng đồng thời</div>
            <div class="rt-sub">Hoạt động trong 30 giây qua</div>
          </div>
          <div class="rt-card">
            <div class="rt-ico" style="color:#3498db"><i class="fa fa-user"></i></div>
            <div class="rt-val" style="color:#3498db" id="v-active">0</div>
            <div class="rt-lbl">Đang online</div>
            <div class="rt-sub">Hoạt động trong 1 phút qua</div>
          </div>
          <div class="rt-card">
            <div class="rt-ico" style="color:#f39c12"><i class="fa fa-bolt"></i></div>
            <div class="rt-val" style="color:#f39c12" id="v-peak">0</div>
            <div class="rt-lbl">Cao nhất (Peak)</div>
            <div class="rt-sub" id="v-peak-at">Chưa ghi nhận</div>
          </div>
          <div class="rt-card">
            <div class="rt-ico" style="color:#2ecc71"><i class="fa fa-globe"></i></div>
            <div class="rt-val" style="color:#2ecc71" id="v-sessions">0</div>
            <div class="rt-lbl">Tổng session (5 phút)</div>
            <div class="rt-sub">Bao gồm cả khách chưa đăng nhập</div>
          </div>
          <div class="rt-card">
            <div class="rt-ico" style="color:#9b59b6"><i class="fa fa-database"></i></div>
            <div class="rt-val" style="color:#9b59b6" id="v-db">0</div>
            <div class="rt-lbl">Tổng session Redis</div>
            <div class="rt-sub">Tổng keys ci_session trong Redis</div>
          </div>
        </div>

        <!-- Row 2: System Health Status -->
        <div class="rt-health">
          <div class="rt-health-main" id="health-main" style="background:#f0f4f8;color:#888">
            <div class="rt-health-icon" id="health-icon"><i class="fa fa-hourglass-half"></i></div>
            <div class="rt-health-label" id="health-label">Đang phân tích...</div>
            <div class="rt-health-desc" id="health-desc">Hệ thống đang thu thập dữ liệu để đánh giá tình trạng hoạt động.</div>
          </div>
          <div class="rt-health-details">
            <h5><i class="fa fa-stethoscope"></i> Chẩn đoán chi tiết hệ thống</h5>
            <!-- Response Time -->
            <div class="rt-hd-row">
              <div class="rt-hd-icon" id="hd-rt-icon" style="background:#eaf2f8;color:#3498db"><i class="fa fa-tachometer"></i></div>
              <div class="rt-hd-info">
                <div class="rt-hd-title">Thời gian phản hồi</div>
                <div class="rt-hd-value" id="hd-rt-val">Đang đo...</div>
              </div>
              <div class="rt-hd-bar">
                <div class="rt-hd-bar-fill" id="hd-rt-bar" style="width:0%;background:#3498db"></div>
              </div>
            </div>
            <!-- Error Rate -->
            <div class="rt-hd-row">
              <div class="rt-hd-icon" id="hd-err-icon" style="background:#eaf7f0;color:#27ae60"><i class="fa fa-exclamation-triangle"></i></div>
              <div class="rt-hd-info">
                <div class="rt-hd-title">Tỷ lệ lỗi</div>
                <div class="rt-hd-value" id="hd-err-val">Chưa có dữ liệu</div>
              </div>
              <div class="rt-hd-bar">
                <div class="rt-hd-bar-fill" id="hd-err-bar" style="width:0%;background:#27ae60"></div>
              </div>
            </div>
            <!-- Load Trend -->
            <div class="rt-hd-row">
              <div class="rt-hd-icon" id="hd-trend-icon" style="background:#f4ecf7;color:#8e44ad"><i class="fa fa-line-chart"></i></div>
              <div class="rt-hd-info">
                <div class="rt-hd-title">Xu hướng tải</div>
                <div class="rt-hd-value" id="hd-trend-val">Đang chờ...</div>
              </div>
              <div class="rt-hd-bar">
                <div class="rt-hd-bar-fill" id="hd-trend-bar" style="width:50%;background:#8e44ad"></div>
              </div>
            </div>
            <!-- Capacity -->
            <div class="rt-hd-row">
              <div class="rt-hd-icon" id="hd-cap-icon" style="background:#fef9e7;color:#f39c12"><i class="fa fa-server"></i></div>
              <div class="rt-hd-info">
                <div class="rt-hd-title">Công suất sử dụng</div>
                <div class="rt-hd-value" id="hd-cap-val">Chưa xác định</div>
              </div>
              <div class="rt-hd-bar">
                <div class="rt-hd-bar-fill" id="hd-cap-bar" style="width:0%;background:#f39c12"></div>
              </div>
            </div>
          </div>
        </div>

        <!-- Row 3: Biểu đồ -->
        <div class="rt-chart-wrap">
          <div class="rt-chart-title"><i class="fa fa-area-chart"></i> Biểu đồ số người dùng theo thời gian (cập nhật mỗi 2 giây)</div>
          <canvas id="rt-chart" height="60"></canvas>
        </div>

        <!-- Row 4: Tóm tắt & Log -->
        <div class="rt-summary" id="rt-tags">
          <span class="rt-tag" style="background:#eaf7f0;color:#27ae60"><i class="fa fa-clock-o"></i> Đang chờ dữ liệu...</span>
        </div>
        <div class="rt-log" id="rt-log">
          <div style="color:#aaa">Nhật ký sẽ hiển thị khi có dữ liệu...</div>
        </div>
      </div>
    </div>

    <script>
      (function() {
        var API = window.base_url ? window.base_url + 'dashboard/active_sessions' : '<?php echo site_url("dashboard/active_sessions"); ?>';
        var MAX = 90,
          labels = [],
          dConc = [],
          dActive = [],
          dSess = [];
        var peak = 0,
          peakTime = '',
          chart = null,
          pollCount = 0,
          startTime = null;
        var prevConc = null,
          logLines = [],
          maxLog = 50,
          timer = null;
        // Health tracking
        var MAX_CAPACITY = 500; // Tested max VUs
        var respTimes = [],
          errCount = 0,
          totalReqs = 0,
          consecErrors = 0;
        var trendHistory = [],
          trendWindow = 5; // last N polls for trend

        function fmt(n) {
          return n < 10 ? '0' + n : '' + n;
        }

        function elapsed() {
          if (!startTime) return '00:00';
          var s = Math.floor((Date.now() - startTime) / 1000);
          return fmt(Math.floor(s / 60)) + ':' + fmt(s % 60);
        }

        function initChart() {
          var c = document.getElementById('rt-chart');
          if (!c || typeof Chart === 'undefined') return;
          chart = new Chart(c.getContext('2d'), {
            type: 'line',
            data: {
              labels: labels,
              datasets: [{
                  label: 'Nguoi dung dong thoi (30s)',
                  data: dConc,
                  borderColor: '#e74c3c',
                  backgroundColor: 'rgba(231,76,60,0.12)',
                  borderWidth: 2.5,
                  pointRadius: 0,
                  fill: true
                },
                {
                  label: 'Dang online (1 phut)',
                  data: dActive,
                  borderColor: '#3498db',
                  backgroundColor: 'rgba(52,152,219,0.06)',
                  borderWidth: 1.5,
                  pointRadius: 0,
                  fill: true,
                  borderDash: [4, 3]
                },
                {
                  label: 'Tong session (5 phut)',
                  data: dSess,
                  borderColor: '#2ecc71',
                  backgroundColor: 'rgba(46,204,113,0.04)',
                  borderWidth: 1,
                  pointRadius: 0,
                  fill: false,
                  borderDash: [2, 2]
                }
              ]
            },
            options: {
              responsive: true,
              maintainAspectRatio: true,
              animation: {
                duration: 150
              },
              tooltips: {
                mode: 'index',
                intersect: false,
                callbacks: {
                  title: function(t) {
                    return 'Thoi gian: ' + t[0].xLabel;
                  },
                  label: function(t, d) {
                    return d.datasets[t.datasetIndex].label + ': ' + t.yLabel + ' nguoi';
                  }
                }
              },
              scales: {
                xAxes: [{
                  display: true,
                  gridLines: {
                    display: false
                  },
                  ticks: {
                    maxTicksLimit: 10,
                    fontSize: 10,
                    fontColor: '#999'
                  }
                }],
                yAxes: [{
                  display: true,
                  gridLines: {
                    color: '#f0f0f0'
                  },
                  ticks: {
                    beginAtZero: true,
                    fontSize: 10,
                    fontColor: '#999',
                    callback: function(v) {
                      return v + ' '
                    }
                  }
                }]
              },
              legend: {
                display: true,
                position: 'top',
                labels: {
                  fontSize: 11,
                  boxWidth: 10,
                  padding: 15
                }
              }
            }
          });
        }

        function addLog(msg, cls) {
          var t = new Date();
          var ts = fmt(t.getHours()) + ':' + fmt(t.getMinutes()) + ':' + fmt(t.getSeconds());
          logLines.unshift('<div class="' + (cls || '') + '">' + ts + ' | ' + msg + '</div>');
          if (logLines.length > maxLog) logLines.pop();
          $('#rt-log').html(logLines.join(''));
        }

        function updateTags(d) {
          var tags = '';
          var conc = d.active_30s;
          // Trạng thái tải
          if (conc === 0) tags += '<span class="rt-tag" style="background:#f0f0f0;color:#888"><i class="fa fa-moon-o"></i> Không có tải</span>';
          else if (conc <= 20) tags += '<span class="rt-tag" style="background:#eaf7f0;color:#27ae60"><i class="fa fa-check"></i> Tải nhẹ (' + conc + ' người)</span>';
          else if (conc <= 100) tags += '<span class="rt-tag" style="background:#fef9e7;color:#f39c12"><i class="fa fa-warning"></i> Tải trung bình (' + conc + ' người)</span>';
          else if (conc <= 300) tags += '<span class="rt-tag" style="background:#fdf2e9;color:#e67e22"><i class="fa fa-fire"></i> Tải cao (' + conc + ' người)</span>';
          else tags += '<span class="rt-tag" style="background:#fdedec;color:#e74c3c"><i class="fa fa-fire-extinguisher"></i> Tải rất cao (' + conc + ' người)</span>';

          // Thời gian giám sát
          tags += '<span class="rt-tag" style="background:#eaf2f8;color:#2980b9"><i class="fa fa-clock-o"></i> ' + elapsed() + '</span>';
          // Số lần poll
          tags += '<span class="rt-tag" style="background:#f4ecf7;color:#8e44ad"><i class="fa fa-refresh"></i> ' + pollCount + ' lần cập nhật</span>';
          // Peak
          if (peak > 0) tags += '<span class="rt-tag" style="background:#fef9e7;color:#d35400"><i class="fa fa-bolt"></i> Peak: ' + peak + ' lúc ' + peakTime + '</span>';

          $('#rt-tags').html(tags);
        }

        function assessHealth(conc, avgRT, errRate, trend) {
          // Returns: {level, color, bg, icon, label, desc, animation}
          // Levels: excellent, good, normal, warning, critical, incident
          var h = {
            level: 'unknown',
            color: '#888',
            bg: '#f0f4f8',
            icon: 'fa-hourglass-half',
            label: 'Dang phan tich...',
            desc: '',
            animation: ''
          };

          // Check for incident first (errors take priority)
          if (consecErrors >= 3) {
            h.level = 'incident';
            h.color = '#fff';
            h.bg = '#c0392b';
            h.icon = 'fa-times-circle';
            h.animation = 'healthCrit 1.5s infinite';
            h.label = 'SỰ CỐ HỆ THỐNG';
            h.desc = 'Không thể kết nối đến máy chủ. Hệ thống có thể đã ngưng hoạt động hoặc mạng bị gián đoạn. Cần kiểm tra ngay Apache, MySQL và kết nối mạng.';
            return h;
          }
          if (errRate > 20) {
            h.level = 'incident';
            h.color = '#fff';
            h.bg = '#e74c3c';
            h.icon = 'fa-exclamation-circle';
            h.animation = 'healthCrit 1.5s infinite';
            h.label = 'SỰ CỐ - LỖI CAO';
            h.desc = 'Tỷ lệ lỗi ' + errRate.toFixed(1) + '% - Hệ thống đang gặp sự cố nghiêm trọng. Nhiều yêu cầu bị lỗi, người dùng có thể không truy cập được. Cần kiểm tra log lỗi và tài nguyên máy chủ.';
            return h;
          }

          // Response time based assessment
          if (avgRT > 3000) {
            h.level = 'critical';
            h.color = '#fff';
            h.bg = '#e74c3c';
            h.icon = 'fa-heartbeat';
            h.animation = 'healthCrit 1.5s infinite';
            h.label = 'QUÁ TẢI NGHIÊM TRỌNG';
            h.desc = 'Thời gian phản hồi ' + avgRT + 'ms - Rất chậm! Máy chủ đang quá tải. Người dùng sẽ gặp tình trạng trang web tải rất chậm hoặc timeout. ần giảm tải hoặc nâng cấp máy chủ.';
            return h;
          }

          // Concurrent users + response time combined assessment
          if (conc === 0 && avgRT < 500 && errRate === 0) {
            h.level = 'idle';
            h.color = '#7f8c8d';
            h.bg = '#f8f9fa';
            h.icon = 'fa-moon-o';
            h.label = 'HẾ THỐNG RÃNH RỖI';
            h.desc = 'Không có người dùng nào đang truy cập. Hệ thống hoạt động bình thường, sẵn sàng phục vụ. Thời gian phản hồi: ' + avgRT + 'ms.';
            return h;
          }
          if (conc <= 30 && avgRT < 500) {
            h.level = 'excellent';
            h.color = '#fff';
            h.bg = '#27ae60';
            h.icon = 'fa-check-circle';
            h.animation = 'healthPulse 2s infinite';
            h.label = 'HOẠT ĐỘNG RẤT TỐT';
            h.desc = 'Hệ thống đang chạy mượt mà với ' + conc + ' người đồng thời. Phản hồi nhanh (' + avgRT + 'ms), không có lỗi. Tài nguyên còn dư để phục vụ thêm nhiều người dùng.';
            return h;
          }
          if (conc <= 100 && avgRT < 1000) {
            h.level = 'good';
            h.color = '#fff';
            h.bg = '#2ecc71';
            h.icon = 'fa-thumbs-up';
            h.animation = 'healthPulse 2s infinite';
            h.label = 'HOẠT ĐỘNG TỐT';
            h.desc = 'Hệ thống đáp ứng tốt với ' + conc + ' người đồng thời. Phản hồi ' + avgRT + 'ms - vẫn nằm trong mức chấp nhận được. ' + (trend > 0 ? 'ải đang tăng, cần theo dõi.' : 'ải ổn định.');
            return h;
          }
          if (conc <= 200 && avgRT < 1500) {
            h.level = 'normal';
            h.color = '#fff';
            h.bg = '#3498db';
            h.icon = 'fa-info-circle';
            h.label = 'TẢI TRUNG BÌNH';
            h.desc = conc + ' người đồng thời, phản hồi ' + avgRT + 'ms. Hệ thống vẫn đáp ứng được nhưng bắt đầu chịu tải. ' + (trend > 0 ? 'Xu hướng tăng - cần chú ý theo dõi thêm.' : 'Xu hướng ổn định.');
            return h;
          }
          if (conc <= 300 && avgRT < 2000) {
            h.level = 'warning';
            h.color = '#fff';
            h.bg = '#f39c12';
            h.icon = 'fa-exclamation-triangle';
            h.animation = 'healthWarn 2s infinite';
            h.label = 'TẢI CAO - CẦN THEO DÕI';
            h.desc = conc + ' người đồng thời, phản hồi ' + avgRT + 'ms. Hệ thống đang chịu tải cao. Người dùng có thể cảm thấy trang web chậm hơn bình thường. ' + (conc > 250 ? 'ần đạt ngưỡng quá tải!' : 'ần theo dõi sát.');
            return h;
          }
          // conc > 300 or avgRT > 2000
          h.level = 'critical';
          h.color = '#fff';
          h.bg = '#e74c3c';
          h.icon = 'fa-warning';
          h.animation = 'healthCrit 1.5s infinite';
          h.label = 'QUÁ TẢI';
          h.desc = conc + ' người đồng thời, phản hồi ' + avgRT + 'ms. Hệ thống đang quá tải! Người dùng sẽ gặp tình trạng chậm, timeout. ' + (errRate > 5 ? 'Đã có ' + errRate.toFixed(1) + '% yêu cầu bị lỗi. ' : '') + 'ần cảnh báo giảm tải hoặc nâng cấp.';
          return h;
        }

        function updateHealth(d, respTime) {
          totalReqs++;
          respTimes.push(respTime);
          if (respTimes.length > 15) respTimes.shift();
          trendHistory.push(d.active_30s);
          if (trendHistory.length > trendWindow) trendHistory.shift();

          // Calculate averages
          var avgRT = Math.round(respTimes.reduce(function(a, b) {
            return a + b;
          }, 0) / respTimes.length);
          var errRate = totalReqs > 0 ? (errCount / totalReqs * 100) : 0;

          // Calculate trend (slope of recent values)
          var trend = 0;
          if (trendHistory.length >= 3) {
            var first = trendHistory.slice(0, Math.floor(trendHistory.length / 2));
            var second = trendHistory.slice(Math.floor(trendHistory.length / 2));
            var avgFirst = first.reduce(function(a, b) {
              return a + b;
            }, 0) / first.length;
            var avgSecond = second.reduce(function(a, b) {
              return a + b;
            }, 0) / second.length;
            trend = avgSecond - avgFirst;
          }

          var h = assessHealth(d.active_30s, avgRT, errRate, trend);

          // Update main health panel
          var $m = $('#health-main');
          $m.css({
            background: h.bg,
            color: h.color
          });
          $m.css('animation', h.animation || 'none');
          $('#health-icon').html('<i class="fa ' + h.icon + '"></i>');
          $('#health-label').text(h.label);
          $('#health-desc').text(h.desc);

          // Response Time indicator
          var rtPct = Math.min(100, avgRT / 3000 * 100);
          var rtColor = avgRT < 500 ? '#27ae60' : avgRT < 1000 ? '#2ecc71' : avgRT < 2000 ? '#f39c12' : '#e74c3c';
          $('#hd-rt-val').text(avgRT + 'ms' + (avgRT < 500 ? ' - Rat nhanh' : avgRT < 1000 ? ' - Nhanh' : avgRT < 2000 ? ' - Cham' : ' - Rat cham!'));
          $('#hd-rt-bar').css({
            width: rtPct + '%',
            background: rtColor
          });
          $('#hd-rt-icon').css({
            background: rtColor + '22',
            color: rtColor
          });

          // Error Rate indicator
          var errPct = Math.min(100, errRate * 5); // Scale: 20% errors = 100% bar
          var errColor = errRate === 0 ? '#27ae60' : errRate < 5 ? '#f39c12' : '#e74c3c';
          var errText = errRate === 0 ? '0% - Khong co loi' : errRate.toFixed(1) + '% (' + errCount + '/' + totalReqs + ' yeu cau bi loi)';
          $('#hd-err-val').text(errText);
          $('#hd-err-bar').css({
            width: errPct + '%',
            background: errColor
          });
          $('#hd-err-icon').css({
            background: errColor + '22',
            color: errColor
          });

          // Trend indicator
          var trendIcon, trendText, trendColor;
          if (trendHistory.length < 3) {
            trendIcon = 'fa-ellipsis-h';
            trendText = 'Dang thu thap du lieu...';
            trendColor = '#8e44ad';
          } else if (trend > 10) {
            trendIcon = 'fa-arrow-up';
            trendText = 'Tang manh (+' + Math.round(trend) + ' nguoi/chu ky)';
            trendColor = '#e74c3c';
          } else if (trend > 2) {
            trendIcon = 'fa-arrow-up';
            trendText = 'Dang tang (+' + Math.round(trend) + ' nguoi/chu ky)';
            trendColor = '#f39c12';
          } else if (trend < -10) {
            trendIcon = 'fa-arrow-down';
            trendText = 'Giam manh (' + Math.round(trend) + ' nguoi/chu ky)';
            trendColor = '#3498db';
          } else if (trend < -2) {
            trendIcon = 'fa-arrow-down';
            trendText = 'Dang giam (' + Math.round(trend) + ' nguoi/chu ky)';
            trendColor = '#2ecc71';
          } else {
            trendIcon = 'fa-minus';
            trendText = 'On dinh';
            trendColor = '#27ae60';
          }
          var trendPct = Math.min(100, Math.max(5, 50 + trend * 2));
          $('#hd-trend-val').text(trendText);
          $('#hd-trend-bar').css({
            width: trendPct + '%',
            background: trendColor
          });
          $('#hd-trend-icon').css({
            background: trendColor + '22',
            color: trendColor
          }).html('<i class="fa ' + trendIcon + '"></i>');

          // Capacity indicator
          var capPct = Math.min(100, d.active_30s / MAX_CAPACITY * 100);
          var capColor = capPct < 30 ? '#27ae60' : capPct < 60 ? '#2ecc71' : capPct < 80 ? '#f39c12' : '#e74c3c';
          var capText = Math.round(capPct) + '% (' + d.active_30s + '/' + MAX_CAPACITY + ' nguoi)';
          if (capPct < 30) capText += ' - Con nhieu du lieu';
          else if (capPct < 60) capText += ' - Vua phai';
          else if (capPct < 80) capText += ' - Kha cao';
          else capText += ' - Gan het!';
          $('#hd-cap-val').text(capText);
          $('#hd-cap-bar').css({
            width: capPct + '%',
            background: capColor
          });
          $('#hd-cap-icon').css({
            background: capColor + '22',
            color: capColor
          });
        }

        function poll() {
          var pollStart = Date.now();
          $.ajax({
            url: API,
            dataType: 'json',
            timeout: 5000,
            success: function(d) {
              var respTime = Date.now() - pollStart;
              consecErrors = 0;
              pollCount++;
              if (!startTime) startTime = Date.now();
              var conc = d.active_30s,
                act = d.active_1m,
                sess = d.total_5m,
                db = d.total_db;

              // Cập nhật cards
              $('#v-concurrent').text(conc);
              $('#v-active').text(act);
              $('#v-sessions').text(sess);
              $('#v-db').text(db);
              $('#rt-time').text(d.time);
              $('#rt-status').text('Live');
              $('#rt-dot').css('background', '#2ecc71');

              // Peak
              if (conc > peak) {
                var oldPeak = peak;
                peak = conc;
                peakTime = d.time;
                if (oldPeak > 0) addLog('Peak moi! ' + peak + ' nguoi dong thoi (truoc do: ' + oldPeak + ')', 'up');
              }
              $('#v-peak').text(peak);
              $('#v-peak-at').text(peak > 0 ? 'Luc ' + peakTime : 'Chua ghi nhan');

              // Log thay đổi
              if (prevConc !== null) {
                var diff = conc - prevConc;
                if (diff > 0) addLog('Tang ' + diff + ' nguoi => ' + conc + ' dong thoi', 'up');
                else if (diff < 0) addLog('Giam ' + Math.abs(diff) + ' nguoi => ' + conc + ' dong thoi', 'down');
                else addLog('On dinh: ' + conc + ' nguoi dong thoi, ' + act + ' online, ' + sess + ' session', 'same');
              } else {
                addLog('Bat dau giam sat: ' + conc + ' dong thoi, ' + act + ' online, ' + sess + ' session', 'up');
              }
              prevConc = conc;

              // Chart
              labels.push(d.time);
              dConc.push(conc);
              dActive.push(act);
              dSess.push(sess);
              if (labels.length > MAX) {
                labels.shift();
                dConc.shift();
                dActive.shift();
                dSess.shift();
              }
              if (chart) chart.update();

              // Tags tóm tắt
              updateTags(d);

              // Health assessment
              updateHealth(d, respTime);
            },
            error: function(x, st, er) {
              errCount++;
              consecErrors++;
              totalReqs++;
              $('#rt-status').text('Loi: ' + st);
              $('#rt-dot').css('background', '#e74c3c');
              addLog('Loi ket noi: ' + st + ' ' + er, 'down');
              // Update health with error state
              var fakeD = {
                active_30s: prevConc || 0,
                active_1m: 0,
                total_5m: 0,
                total_db: 0
              };
              updateHealth(fakeD, 5000);
            }
          });
        }

        function resetAll() {
          peak = 0;
          peakTime = '';
          prevConc = null;
          pollCount = 0;
          startTime = null;
          labels.length = 0;
          dConc.length = 0;
          dActive.length = 0;
          dSess.length = 0;
          logLines.length = 0;
          // Reset health tracking
          respTimes.length = 0;
          errCount = 0;
          totalReqs = 0;
          consecErrors = 0;
          trendHistory.length = 0;
          $('#v-concurrent,#v-active,#v-peak,#v-sessions,#v-db').text('0');
          $('#v-peak-at').text('Chua ghi nhan');
          $('#rt-time').text('--:--:--');
          $('#rt-status').text('Da reset, dang cho...');
          $('#rt-log').html('<div style="color:#aaa">Da reset. Du lieu bat dau tu lan poll tiep theo...</div>');
          $('#rt-tags').html('<span class="rt-tag" style="background:#f0f0f0;color:#888"><i class="fa fa-refresh"></i> Da reset</span>');
          // Reset health panel
          $('#health-main').css({
            background: '#f0f4f8',
            color: '#888',
            animation: 'none'
          });
          $('#health-icon').html('<i class="fa fa-hourglass-half"></i>');
          $('#health-label').text('Dang phan tich...');
          $('#health-desc').text('He thong dang thu thap du lieu de danh gia tinh trang hoat dong.');
          $('#hd-rt-val').text('Dang do...');
          $('#hd-rt-bar').css('width', '0%');
          $('#hd-err-val').text('Chua co du lieu');
          $('#hd-err-bar').css('width', '0%');
          $('#hd-trend-val').text('Dang cho...');
          $('#hd-trend-bar').css({
            width: '50%',
            background: '#8e44ad'
          });
          $('#hd-cap-val').text('Chua xac dinh');
          $('#hd-cap-bar').css('width', '0%');
          if (chart) chart.update();
        }

        $(function() {
          initChart();
          poll();
          timer = setInterval(poll, 2000);
          $('#rt-reset').on('click', function() {
            resetAll();
          });
        });
      })();
    </script>
  <?php endif; ?>

  <div class="row">

    <div class="col-md-4">
      <div class="panel panel-info">
        <div class="panel-heading">
          <div class="row">
            <div class="col-xs-3">
              <i class="fa fa-users fa-5x"></i>
            </div>
            <div class="col-xs-9 text-right">
              <div class="huge"><?php echo $num_users; ?></div>
              <div><?php echo $this->lang->line('no_registered_user'); ?> </div>
            </div>
          </div>
        </div>
        <a href="<?php echo site_url('user'); ?>">
          <div class="panel-footer">
            <span class="pull-left">Danh sách người dùng </span>
            <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
            <div class="clearfix"></div>
          </div>
        </a>
      </div>
    </div>


    <div class="col-md-4">
      <div class="panel panel-danger">
        <div class="panel-heading">
          <div class="row">
            <div class="col-xs-3">
              <i class="fa fa-book fa-5x"></i>
            </div>
            <div class="col-xs-9 text-right">
              <div class="huge"><?php echo $num_quiz; ?></div>
              <div><?php echo $this->lang->line('no_registered_quiz'); ?> </div>
            </div>
          </div>
        </div>
        <a href="<?php echo site_url('quiz'); ?>">
          <div class="panel-footer">
            <span class="pull-left">Danh sách đề thi </span>
            <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
            <div class="clearfix"></div>
          </div>
        </a>
      </div>
    </div>

    <div class="col-md-4">
      <div class="panel panel-warning">
        <div class="panel-heading">
          <div class="row">
            <div class="col-xs-3">
              <i class="fa fa-file-text fa-5x"></i>
            </div>
            <div class="col-xs-9 text-right">
              <div class="huge"><?php echo $num_qbank; ?></div>
              <div><?php echo $this->lang->line('no_questions_qbank'); ?></div>
            </div>
          </div>
        </div>
        <a href="<?php echo site_url('qbank'); ?>">
          <div class="panel-footer">Danh sách câu hỏi </span>
            <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
            <div class="clearfix"></div>
          </div>
        </a>
      </div>





    </div>

    <div class="row"></div>









    <div class="row">

      <div class="col-md-4">
        <div class="panel panel-info">
          <div class="panel-heading" style="background-color:#72B159;text-align:center;">
            <div class="font-size-34"> <strong style="color:#ffffff;"><?php echo $active_users; ?></strong>
              <br>
              <small class="font-weight-light text-muted" style="font-size:18px;color:#eeeeee;">Tài khoản còn hiệu lực </small>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="panel panel">
          <div class="panel-heading" style="background-color:#DB5949;text-align:center;">
            <div class="font-size-34"> <strong style="color:#ffffff;"><?php echo $inactive_users; ?></strong>
              <br>
              <small class="font-weight-light text-muted" style="font-size:18px;color:#eeeeee;  "> Tài khoản bị khóa</small>
            </div>
          </div>
        </div>
      </div>


      <div class="col-md-4">
        <div class="panel panel">
          <div class="panel-heading" style="background-color:#DB5949;text-align:center;">
            <div class="font-size-34"> <strong style="color:#ffffff;"><?php echo $inactive_users; ?></strong>
              <br>
              <small class="font-weight-light text-muted" style="font-size:18px;color:#eeeeee;  "> <?php echo $this->lang->line('users'); ?> <?php echo $this->lang->line('inactive'); ?></small>
            </div>
          </div>
        </div>
      </div>

    </div>
    <!-- recent users -->
    <div class="row">

      <div class="panel">
        <div class="panel-heading">
          <div class="panel-title"><?php echo $this->lang->line('recently_registered'); ?></div>
        </div>
        <div class="table-responsive">
          <table class="table table-striped valign-middle">
            <thead>
              <tr>
                <th><?php echo $this->lang->line('studentid'); ?></th>
                <th class="text-xs-right"><?php echo $this->lang->line('first_name'); ?> <?php echo $this->lang->line('last_name'); ?></th>
                <th><?php echo $this->lang->line('email'); ?></th>

                <th class="text-xs-right"><?php echo $this->lang->line('contact_no'); ?></th>
                <th class="text-xs-right"><?php echo $this->lang->line('classid'); ?></th>
                <th></th>
              </tr>
            </thead>
            <tbody>
              <?php
              if (count($result) == 0) {
              ?>
                <tr>
                  <td colspan="3"><?php echo $this->lang->line('no_record_found'); ?></td>
                </tr>
              <?php
              }
              foreach ($result as $key => $val) {
              ?>
                <tr>
                  <td>
                    <a href="<?php echo site_url('user/edit_user/' . $val['uid']); ?>"><?php echo $val['studentid']; ?> <?php echo $val['wp_user']; ?></a>
                  </td>
                  <td class="text-xs-right">
                    <?php echo $val['first_name']; ?> <?php echo $val['last_name']; ?>
                  </td>
                  <td class="text-xs-right"><?php echo $val['email']; ?></td>
                  <td class="text-xs-right"><?php echo $val['contact_no']; ?></td>
                  <td class="text-xs-right"><?php echo $val['classid']; ?></td>


                </tr>

              <?php
              }
              ?>

            </tbody>
          </table>
        </div>
      </div>

      <!-- recent users -->

    </div>








  </div>