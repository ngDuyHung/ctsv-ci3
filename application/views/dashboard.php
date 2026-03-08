<div class="container">



  <div id="update_notice"></div>

  <!-- Real-time Active Sessions Monitor -->
  <?php $logged_in = $this->session->userdata('logged_in');
  if (isset($logged_in['su']) && $logged_in['su'] == '1'): ?>

  
  <script>
  function clearServerCache() {
    var btn = document.getElementById('btn-clear-cache');
    var msg = document.getElementById('clear-cache-msg');
    btn.disabled = true;
    msg.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Đang xóa...';
    fetch('<?php echo base_url('dashboard/clear_cache'); ?>', {method: 'GET'})
      .then(function(r){ return r.json(); })
      .then(function(data){
        if (data.success) {
          var info = Object.keys(data.results).map(function(k){ return k + ': ' + data.results[k]; }).join(' | ');
          msg.innerHTML = '<span style="color:green"><i class="fa fa-check"></i> Cache đã xóa! (' + info + ') – Nhấn Ctrl+Shift+R để reload trình duyệt.</span>';
        } else {
          msg.innerHTML = '<span style="color:red">Lỗi!</span>';
        }
        btn.disabled = false;
      })
      .catch(function(){ msg.innerHTML = '<span style="color:red">Lỗi kết nối!</span>'; btn.disabled = false; });
  }
  </script>
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
            <div class="rt-lbl">Phiên đồng thời</div>
            <div class="rt-sub">Sessions hoạt động trong 30s (<span id="v-users30">0</span> users)</div>
          </div>
          <div class="rt-card">
            <div class="rt-ico" style="color:#3498db"><i class="fa fa-user"></i></div>
            <div class="rt-val" style="color:#3498db" id="v-active">0</div>
            <div class="rt-lbl">Đang online</div>
            <div class="rt-sub">Sessions trong 1 phút (<span id="v-users1m">0</span> users)</div>
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
          if (consecErrors >= 400) {
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
          // MAX_CAPACITY = 500 VUs (đã kiểm thử thực tế)
          // Ngưỡng: 0 → nhàn / ≤30 → rất tốt / ≤150 → tốt / ≤300 → trung bình / ≤450 → cao / >450 hoặc RT cao → quá tải
          if (conc === 0 && avgRT < 500 && errRate === 0) {
            h.level = 'idle';
            h.color = '#7f8c8d';
            h.bg = '#f8f9fa';
            h.icon = 'fa-moon-o';
            h.label = 'HỆ THỐNG NHÀN RỖI';
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
          if (conc <= 450 && avgRT < 800) {
            h.level = 'good';
            h.color = '#fff';
            h.bg = '#2ecc71';
            h.icon = 'fa-thumbs-up';
            h.animation = 'healthPulse 2s infinite';
            h.label = 'HOẠT ĐỘNG TỐT';
            h.desc = 'Hệ thống đáp ứng tốt với ' + conc + ' người đồng thời. Phản hồi ' + avgRT + 'ms - nằm trong mức chấp nhận được. ' + (trend > 0 ? 'Tải đang tăng, cần theo dõi.' : 'Tải ổn định.');
            return h;
          }
          if (conc <= 600 && avgRT < 1500) {
            h.level = 'normal';
            h.color = '#fff';
            h.bg = '#3498db';
            h.icon = 'fa-info-circle';
            h.label = 'TẢI TRUNG BÌNH';
            h.desc = conc + ' người đồng thời, phản hồi ' + avgRT + 'ms. Hệ thống vẫn đáp ứng tốt nhưng bắt đầu chịu tải. ' + (trend > 0 ? 'Xu hướng tăng - cần chú ý theo dõi thêm.' : 'Xu hướng ổn định.');
            return h;
          }
          if (conc <= 2050 && avgRT < 2500) {
            h.level = 'warning';
            h.color = '#fff';
            h.bg = '#f39c12';
            h.icon = 'fa-exclamation-triangle';
            h.animation = 'healthWarn 2s infinite';
            h.label = 'TẢI CAO - CẦN THEO DÕI';
            h.desc = conc + ' người đồng thời, phản hồi ' + avgRT + 'ms. Hệ thống đang chịu tải cao. Người dùng có thể cảm thấy trang web chậm hơn bình thường. ' + (conc > 350 ? 'Gần đạt ngưỡng quá tải!' : 'Cần theo dõi sát.');
            return h;
          }
          // // conc > 450 hoặc avgRT > 2500
          // h.level = 'critical';
          // h.color = '#fff';
          // h.bg = '#e74c3c';
          // h.icon = 'fa-warning';
          // h.animation = 'healthCrit 1.5s infinite';
          // h.label = 'QUÁ TẢI';
          // h.desc = conc + ' người đồng thời, phản hồi ' + avgRT + 'ms. Hệ thống đang quá tải! Người dùng sẽ gặp tình trạng chậm, timeout. ' + (errRate > 5 ? 'Đã có ' + errRate.toFixed(1) + '% yêu cầu bị lỗi. ' : '') + 'Cần cảnh báo giảm tải hoặc nâng cấp.';
          // return h;
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
          var rtColor = avgRT < 500 ? '#27ae60' : avgRT < 2000 ? '#2ecc71' : avgRT < 2500 ? '#f39c12' : '#e74c3c';
          $('#hd-rt-val').text(avgRT + 'ms' + (avgRT < 500 ? ' - Rat nhanh' : avgRT < 2000 ? ' - Nhanh' : avgRT < 2500 ? ' - Cham' : ' - Rat cham!'));
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
              $('#v-users30').text(d.users_30s || 0);
              $('#v-users1m').text(d.users_1m || 0);
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

    <!-- K6 Load Test Results Panel -->
    <style>
      #k6-panel {
        margin-top: 18px
      }

      .k6-hdr {
        background: linear-gradient(135deg, #2c3e50, #3498db);
        color: #fff;
        border-radius: 8px 8px 0 0;
        padding: 10px 16px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap
      }

      .k6-hdr h4 {
        margin: 0;
        font-size: 16px;
        font-weight: 600
      }

      .k6-hdr-right {
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 12px
      }

      .k6-body {
        background: #f7f8fc;
        border: 1px solid #dde;
        border-top: 0;
        border-radius: 0 0 8px 8px;
        padding: 14px
      }

      .k6-empty {
        text-align: center;
        padding: 30px;
        color: #aaa;
        font-size: 14px
      }

      .k6-verdict {
        border-radius: 10px;
        padding: 16px 20px;
        margin-bottom: 14px;
        display: flex;
        align-items: center;
        gap: 14px
      }

      .k6-verdict-icon {
        font-size: 42px
      }

      .k6-verdict-info h3 {
        margin: 0 0 4px;
        font-size: 18px;
        font-weight: 700
      }

      .k6-verdict-info p {
        margin: 0;
        font-size: 13px;
        line-height: 1.5;
        opacity: .9
      }

      .k6-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        gap: 10px;
        margin-bottom: 14px
      }

      .k6-metric {
        background: #fff;
        border-radius: 8px;
        padding: 12px;
        border: 1px solid #e8e8e8
      }

      .k6-metric-title {
        font-size: 11px;
        color: #888;
        margin-bottom: 4px;
        display: flex;
        align-items: center;
        gap: 4px
      }

      .k6-metric-title i {
        font-size: 10px
      }

      .k6-metric-val {
        font-size: 22px;
        font-weight: 700;
        line-height: 1.2
      }

      .k6-metric-desc {
        font-size: 10px;
        color: #aaa;
        margin-top: 3px;
        line-height: 1.4
      }

      .k6-metric-badge {
        display: inline-block;
        padding: 1px 6px;
        border-radius: 8px;
        font-size: 9px;
        font-weight: 600;
        margin-left: 4px;
        vertical-align: middle
      }

      .k6-checks {
        background: #fff;
        border-radius: 8px;
        padding: 12px;
        border: 1px solid #e8e8e8;
        margin-bottom: 14px
      }

      .k6-checks h5 {
        margin: 0 0 8px;
        font-size: 13px;
        font-weight: 600;
        color: #555
      }

      .k6-check-bar {
        height: 8px;
        border-radius: 4px;
        background: #f0f0f0;
        overflow: hidden;
        margin-bottom: 6px
      }

      .k6-check-fill {
        height: 100%;
        border-radius: 4px;
        transition: width .3s
      }

      .k6-check-stats {
        font-size: 11px;
        color: #888
      }

      .k6-history {
        background: #fff;
        border-radius: 8px;
        padding: 12px;
        border: 1px solid #e8e8e8
      }

      .k6-history h5 {
        margin: 0 0 8px;
        font-size: 13px;
        font-weight: 600;
        color: #555
      }

      .k6-history table {
        width: 100%;
        font-size: 11px;
        border-collapse: collapse
      }

      .k6-history th {
        text-align: left;
        padding: 4px 6px;
        border-bottom: 2px solid #eee;
        color: #888;
        font-weight: 600
      }

      .k6-history td {
        padding: 4px 6px;
        border-bottom: 1px solid #f5f5f5
      }

      .k6-history tr:hover td {
        background: #fafafa
      }

      .k6-del-btn {
        background: none;
        border: none;
        color: #ccc;
        cursor: pointer;
        padding: 2px 5px;
        border-radius: 4px;
        font-size: 12px;
        line-height: 1;
        transition: color .15s, background .15s
      }

      .k6-del-btn:hover {
        color: #e74c3c;
        background: #fdf0f0
      }
    </style>
    <div id="k6-panel">
      <div class="k6-hdr">
        <h4><i class="fa fa-flask"></i>&nbsp; KẾT QUẢ LOAD TEST (k6)</h4>
        <div class="k6-hdr-right">
          <span id="k6-last-time" style="opacity:.8">Chưa có test</span>
          <button class="rt-btn" id="k6-refresh" title="Tải lại kết quả"><i class="fa fa-refresh"></i> Refresh</button>
        </div>
      </div>
      <div class="k6-body">
        <div id="k6-content">
          <div class="k6-empty"><i class="fa fa-flask" style="font-size:40px;display:block;margin-bottom:10px"></i>Chưa có kết quả load test nào.<br><small>Chạy <code>k6 run script.js</code> để bắt đầu.</small></div>
        </div>
      </div>
    </div>

    <script>
      (function() {
        var K6_API = window.base_url ? window.base_url + 'dashboard/get_k6_result' : '<?php echo site_url("dashboard/get_k6_result"); ?>';

        function fmtMs(ms) {
          if (ms >= 1000) return (ms / 1000).toFixed(2) + 's';
          return Math.round(ms) + 'ms';
        }

        function fmtBytes(b) {
          if (b >= 1048576) return (b / 1048576).toFixed(1) + ' MB';
          if (b >= 1024) return (b / 1024).toFixed(1) + ' KB';
          return b + ' B';
        }

        function fmtDuration(ms) {
          var s = Math.floor(ms / 1000);
          var m = Math.floor(s / 60);
          s = s % 60;
          return m + ' phút ' + s + ' giây';
        }

        function fmtTime(iso) {
          if (!iso) return '--';
          var d = new Date(iso);
          return d.toLocaleDateString('vi-VN') + ' ' + d.toLocaleTimeString('vi-VN');
        }

        function badge(text, color) {
          return '<span class="k6-metric-badge" style="background:' + color + '22;color:' + color + '">' + text + '</span>';
        }

        function rateColor(pct) {
          return pct < 0.5 ? '#27ae60' : pct < 2 ? '#f39c12' : '#e74c3c';
        }

        function rtColor(ms) {
          return ms < 500 ? '#27ae60' : ms < 1000 ? '#2ecc71' : ms < 2000 ? '#f39c12' : '#e74c3c';
        }

        function renderResult(d) {
          if (!d) return '<div class="k6-empty"><i class="fa fa-flask" style="font-size:40px;display:block;margin-bottom:10px"></i>Chưa có kết quả load test nào.<br><small>Chạy <code>k6 run script.js</code> để bắt đầu.</small></div>';

          var html = '';
          var p95 = d.http_req_duration_p95 || 0;
          var failRate = (d.http_req_failed_rate || 0) * 100;
          var checkRate = d.checks_total > 0 ? (d.checks_passed / d.checks_total * 100) : 100;
          var vus = d.vus_max || 0;

          // === VERDICT ===
          var vLevel, vBg, vColor, vIcon, vTitle, vDesc;
          if (failRate > 5 || p95 > 5000) {
            vLevel = 'critical';
            vBg = '#e74c3c';
            vColor = '#fff';
            vIcon = 'fa-times-circle';
            vTitle = 'KHÔNG ĐẠT - Hệ thống gặp vấn đề nghiêm trọng';
            vDesc = 'Với ' + vus + ' người dùng đồng thời, hệ thống có tỷ lệ lỗi ' + failRate.toFixed(2) + '% và thời gian phản hồi p95=' + fmtMs(p95) + '. Hệ thống không đáp ứng được mức tải này. Cần tối ưu hoặc nâng cấp hạ tầng.';
          } else if (failRate > 1 || p95 > 3000) {
            vLevel = 'warning';
            vBg = '#f39c12';
            vColor = '#fff';
            vIcon = 'fa-exclamation-triangle';
            vTitle = 'CẦN CẢI THIỆN - Hệ thống chịu tải kém';
            vDesc = 'Với ' + vus + ' người dùng, phản hồi p95=' + fmtMs(p95) + ', lỗi ' + failRate.toFixed(2) + '%. Người dùng sẽ cảm thấy chậm. Nên tối ưu truy vấn DB, caching, hoặc cấu hình server.';
          } else if (p95 > 1500) {
            vLevel = 'acceptable';
            vBg = '#3498db';
            vColor = '#fff';
            vIcon = 'fa-info-circle';
            vTitle = 'CHẤP NHẬN ĐƯỢC - Hệ thống đáp ứng được nhưng cần cải thiện';
            vDesc = 'Với ' + vus + ' người dùng, phản hồi p95=' + fmtMs(p95) + ', không lỗi. Hệ thống hoạt động nhưng tốc độ chưa lý tưởng.';
          } else if (p95 > 500) {
            vLevel = 'good';
            vBg = '#2ecc71';
            vColor = '#fff';
            vIcon = 'fa-thumbs-up';
            vTitle = 'TỐT - Hệ thống đáp ứng tốt';
            vDesc = 'Với ' + vus + ' người dùng đồng thời, thời gian phản hồi p95=' + fmtMs(p95) + ' — nhanh và ổn định. Không có lỗi. Hệ thống sẵn sàng phục vụ production.';
          } else {
            vLevel = 'excellent';
            vBg = '#27ae60';
            vColor = '#fff';
            vIcon = 'fa-check-circle';
            vTitle = 'XUẤT SẮC - Hiệu năng tuyệt vời';
            vDesc = 'Với ' + vus + ' người dùng đồng thời, phản hồi chỉ p95=' + fmtMs(p95) + ' — cực nhanh! Hệ thống có thể phục vụ nhiều người dùng hơn nữa.';
          }

          html += '<div class="k6-verdict" style="background:' + vBg + ';color:' + vColor + '">';
          html += '<div class="k6-verdict-icon"><i class="fa ' + vIcon + '"></i></div>';
          html += '<div class="k6-verdict-info"><h3>' + vTitle + '</h3><p>' + vDesc + '</p></div>';
          html += '</div>';

          // === METRICS GRID ===
          html += '<div class="k6-grid">';

          // VUs
          html += '<div class="k6-metric"><div class="k6-metric-title"><i class="fa fa-users"></i> Số người dùng ảo (VUs)</div>';
          html += '<div class="k6-metric-val" style="color:#8e44ad">' + vus + '</div>';
          html += '<div class="k6-metric-desc">Số người truy cập đồng thời mô phỏng trong test</div></div>';

          // Iterations
          html += '<div class="k6-metric"><div class="k6-metric-title"><i class="fa fa-repeat"></i> Số lượt thực hiện</div>';
          html += '<div class="k6-metric-val" style="color:#2c3e50">' + (d.iterations || 0) + '</div>';
          html += '<div class="k6-metric-desc">Mỗi lượt = 1 user hoàn thành flow (login→xem trang→quiz)</div></div>';

          // HTTP Requests
          html += '<div class="k6-metric"><div class="k6-metric-title"><i class="fa fa-exchange"></i> Tổng HTTP request</div>';
          html += '<div class="k6-metric-val" style="color:#2980b9">' + (d.http_reqs || 0) + '</div>';
          html += '<div class="k6-metric-desc">Throughput: ' + (d.http_reqs_rate || 0).toFixed(1) + ' req/giây</div></div>';

          // Duration
          html += '<div class="k6-metric"><div class="k6-metric-title"><i class="fa fa-clock-o"></i> Thời gian test</div>';
          html += '<div class="k6-metric-val" style="color:#7f8c8d">' + fmtDuration(d.duration || 0) + '</div>';
          html += '<div class="k6-metric-desc">Tổng thời gian chạy test từ đầu đến cuối</div></div>';

          // Response Time p95
          var rtBadge = p95 < 500 ? badge('Xuất sắc', '#27ae60') : p95 < 2500 ? badge('Tốt', '#2ecc71') : p95 < 3000 ? badge('Chậm', '#f39c12') : badge('Rất chậm', '#e74c3c');
          html += '<div class="k6-metric"><div class="k6-metric-title"><i class="fa fa-tachometer"></i> Tốc độ phản hồi (p95)' + rtBadge + '</div>';
          html += '<div class="k6-metric-val" style="color:' + rtColor(p95) + '">' + fmtMs(p95) + '</div>';
          html += '<div class="k6-metric-desc">95% requests nhanh hơn giá trị này. Avg=' + fmtMs(d.http_req_duration_avg || 0) + ', Med=' + fmtMs(d.http_req_duration_med || 0) + '</div></div>';

          // Response Time breakdown
          html += '<div class="k6-metric"><div class="k6-metric-title"><i class="fa fa-bar-chart"></i> Phân bố thời gian phản hồi</div>';
          html += '<div class="k6-metric-val" style="font-size:14px;color:#555">';
          html += 'Min: <b style="color:#27ae60">' + fmtMs(d.http_req_duration_min || 0) + '</b> &nbsp;';
          html += 'p90: <b style="color:' + rtColor(d.http_req_duration_p90 || 0) + '">' + fmtMs(d.http_req_duration_p90 || 0) + '</b> &nbsp;';
          html += 'Max: <b style="color:' + rtColor(d.http_req_duration_max || 0) + '">' + fmtMs(d.http_req_duration_max || 0) + '</b>';
          html += '</div>';
          html += '<div class="k6-metric-desc">Min = nhanh nhất, p90 = 90% nhanh hơn, Max = chậm nhất</div></div>';

          // Error rate
          var errBadge = failRate === 0 ? badge('Hoàn hảo', '#27ae60') : failRate < 1 ? badge('Chấp nhận', '#f39c12') : badge('Cao', '#e74c3c');
          html += '<div class="k6-metric"><div class="k6-metric-title"><i class="fa fa-exclamation-triangle"></i> Tỷ lệ lỗi HTTP' + errBadge + '</div>';
          html += '<div class="k6-metric-val" style="color:' + rateColor(failRate) + '">' + failRate.toFixed(2) + '%</div>';
          html += '<div class="k6-metric-desc">' + (failRate === 0 ? 'Không có request nào bị lỗi — tuyệt vời!' : 'Có ' + Math.round(failRate / 100 * (d.http_reqs || 0)) + ' requests bị lỗi (timeout, 5xx)') + '</div></div>';

          // Data transfer
          html += '<div class="k6-metric"><div class="k6-metric-title"><i class="fa fa-download"></i> Dữ liệu truyền tải</div>';
          html += '<div class="k6-metric-val" style="font-size:16px;color:#16a085">↓ ' + fmtBytes(d.data_received || 0) + ' / ↑ ' + fmtBytes(d.data_sent || 0) + '</div>';
          html += '<div class="k6-metric-desc">Tổng lượng dữ liệu nhận (↓) và gửi (↑) trong test</div></div>';

          html += '</div>'; // end grid

          // === CHECKS ===
          html += '<div class="k6-checks"><h5><i class="fa fa-check-square-o"></i> Kiểm tra nội dung trang (Content Checks)</h5>';
          var checkPct = d.checks_total > 0 ? (d.checks_passed / d.checks_total * 100) : 100;
          var checkColor = checkPct >= 99 ? '#27ae60' : checkPct >= 95 ? '#f39c12' : '#e74c3c';
          html += '<div class="k6-check-bar"><div class="k6-check-fill" style="width:' + checkPct + '%;background:' + checkColor + '"></div></div>';
          html += '<div class="k6-check-stats">';
          html += '<span style="color:' + checkColor + ';font-weight:700">' + checkPct.toFixed(1) + '% đạt</span> — ';
          html += '<span style="color:#27ae60">✓ ' + (d.checks_passed || 0) + ' đạt</span> &nbsp; ';
          html += '<span style="color:#e74c3c">✗ ' + (d.checks_failed || 0) + ' lỗi</span> &nbsp; ';
          html += '(Tổng: ' + (d.checks_total || 0) + ' kiểm tra)';
          html += '</div>';
          html += '<div class="k6-metric-desc" style="margin-top:6px">Mỗi request kiểm tra: status 200 và nội dung trang đúng (có chứa text "Thông báo", "Bài thi"...). Nếu thấp = session bị mất hoặc server trả sai nội dung.</div>';
          html += '</div>';

          return html;
        }

        function renderHistory(list) {
          if (!list || list.length === 0) return '';
          var html = '<div class="k6-history"><h5><i class="fa fa-history"></i> Lịch sử các lần test gần nhất (tối đa 20)</h5>';
          html += '<table><thead><tr><th>Thời gian</th><th>VUs</th><th>Requests</th><th>p95</th><th>Lỗi</th><th>Checks</th><th>Kết quả</th><th></th></tr></thead><tbody>';
          for (var i = 0; i < list.length; i++) {
            var r = list[i];
            var p95 = r.http_req_duration_p95 || 0;
            var fail = (r.http_req_failed_rate || 0) * 100;
            var chkPct = r.checks_total > 0 ? (r.checks_passed / r.checks_total * 100) : 100;
            var verdict, vColor;
            if (fail > 5 || p95 > 5000) {
              verdict = 'KHÔNG ĐẠT';
              vColor = '#e74c3c';
            } else if (fail > 1 || p95 > 3000) {
              verdict = 'CẢI THIỆN';
              vColor = '#f39c12';
            } else if (p95 > 1500) {
              verdict = 'CHẤP NHẬN';
              vColor = '#3498db';
            } else if (p95 > 500) {
              verdict = 'TỐT';
              vColor = '#2ecc71';
            } else {
              verdict = 'XUẤT SẮC';
              vColor = '#27ae60';
            }

            html += '<tr data-ts="' + (r.timestamp || '') + '">';
            html += '<td>' + fmtTime(r.timestamp) + '</td>';
            html += '<td><b>' + (r.vus_max || 0) + '</b></td>';
            html += '<td>' + (r.http_reqs || 0) + '</td>';
            html += '<td style="color:' + rtColor(p95) + ';font-weight:600">' + fmtMs(p95) + '</td>';
            html += '<td style="color:' + rateColor(fail) + '">' + fail.toFixed(2) + '%</td>';
            html += '<td>' + chkPct.toFixed(1) + '%</td>';
            html += '<td><span style="color:' + vColor + ';font-weight:700">' + verdict + '</span></td>';
            html += '<td><button class="k6-del-btn" data-ts="' + (r.timestamp || '') + '" title="Xóa dòng này"><i class="fa fa-trash"></i></button></td>';
            html += '</tr>';
          }
          html += '</tbody></table></div>';
          return html;
        }

        function loadK6() {
          $.getJSON(K6_API, function(resp) {
            var html = renderResult(resp.last);
            html += renderHistory(resp.history);
            $('#k6-content').html(html);
            if (resp.last) {
              $('#k6-last-time').text('Lần test gần nhất: ' + fmtTime(resp.last.timestamp));
            }
          }).fail(function() {
            // silent fail
          });
        }

        var DEL_API = window.base_url ? window.base_url + 'dashboard/delete_k6_result' : '<?php echo site_url("dashboard/delete_k6_result"); ?>';

        $(document).on('click', '.k6-del-btn', function() {
          var ts = $(this).data('ts');
          var $tr = $(this).closest('tr');
          $tr.css('opacity', '0.4');
          $.post(DEL_API, {
            ts: ts
          }, function(resp) {
            if (resp && resp.status === 'ok') {
              $tr.remove();
            } else {
              $tr.css('opacity', '1');
            }
          }, 'json').fail(function() {
            $tr.css('opacity', '1');
          });
        });

        $(function() {
          loadK6();
          // Auto refresh khi tab visible
          setInterval(loadK6, 15000);
          $('#k6-refresh').on('click', loadK6);
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