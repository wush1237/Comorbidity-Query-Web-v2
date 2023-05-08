<!DOCTYPE html>
<html data-wf-page="63845ae183c0cdbfe17a04f9" data-wf-site="63845ae083c0cd91137a04f3">

<head>
  <meta charset="utf-8">
  <title>疾病趨勢圖</title>
  <meta
    content="Stone Template is an awesome dark and beige muted theme for an agency or any other professional service."
    name="description">
  <meta content="width=device-width, initial-scale=1" name="viewport">
  <meta content="Webflow" name="generator">
  <link href="css/normalize.css" rel="stylesheet" type="text/css">
  <link href="css/webflow.css" rel="stylesheet" type="text/css">
  <link href="css/comorbiditymiia.webflow.css" rel="stylesheet" type="text/css">
  <script src="https://ajax.googleapis.com/ajax/libs/webfont/1.6.26/webfont.js" type="text/javascript"></script>
  <script
    type="text/javascript">WebFont.load({ google: { families: ["Vollkorn:400,400italic,700,700italic", "Roboto Condensed:300,regular,700", "Roboto:300,regular,500"] } });</script>
  <!-- [if lt IE 9]><script src="https://cdnjs.cloudflare.com/ajax/libs/html5shiv/3.7.3/html5shiv.min.js" type="text/javascript"></script><![endif] -->
  <script
    type="text/javascript">!function (o, c) { var n = c.documentElement, t = " w-mod-"; n.className += t + "js", ("ontouchstart" in o || o.DocumentTouch && c instanceof DocumentTouch) && (n.className += t + "touch") }(window, document);</script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script src="js/main.js"></script>
  <link href="images/favicon.ico" rel="shortcut icon" type="image/x-icon">
  <link href="images/webclip.png" rel="apple-touch-icon">
</head>

<body>
  <div data-collapse="medium" data-animation="default" data-duration="400" data-easing="ease" data-easing2="ease"
    role="banner" class="navbar w-nav">
    <div class="w-container">
      <a href="index.html" class="brand w-clearfix w-nav-brand"><img src="images/logo.png" width="30" alt=""
          class="stone-logo">
        <div class="logo-text">第2組</div>
      </a>
      <nav role="navigation" class="nav-menu w-nav-menu">
        <a href="index.html" class="nav-link w-nav-link">首頁</a>
        <a href="icdID.php" class="nav-link w-nav-link ">共病性查詢</a>
        <a href="icdCode.php" class="nav-link w-nav-link ">ICD共病性</a>
        <a href="icdName.php" class="nav-link w-nav-link">ICD對照查詢</a>
        <a href="dataChart.php" aria-current="page" class="nav-link w-nav-link  w--current">疾病趨勢圖</a>
        <a href="contact.html" class="nav-link w-nav-link">關於我們</a>
        <a href="" aria-current="page"
          class="nav-link social-icons w-hidden-medium w-hidden-small w-hidden-tiny w-inline-block w-nav-link"><img
            src="images/icons8-privacy-64-1.png" width="25" alt=""></a>
      </nav>

      <div class="menu-button w-nav-button">
        <div class="menu-icon w-icon-nav-menu"></div>
      </div>
    </div>
  </div>
  <div class="section grey wf-section">
    <div class="w-container">
      <div class="w-row">
        <div class="w-col w-col-6">
          <h1>病歷數據分析-趨勢圖</h1>
          <div class="horizontal-bar beige"></div>
          <div class="subheading">根據百萬筆資料，統計分析之疾病趨勢圖</div>
        </div>
        <div class="back-to-work-column w-col w-col-6"></div>
      </div>
    </div>
  </div>
  <div class="section wf-section">
    <div class="container-3 w-container">
      <div class="number">search</div>
      <h2>填寫查詢資料</h2>

      <form id="wf-form-icd-from" method="POST" action="">
        <label for="gender-select">性別:</label>
        <select name="gender" id="gender-select" class="w-select">
        <option value="ALL">請選擇性別</option>
          <option value="M">男性</option>
          <option value="F">女性</option>
          <option value="ALL">不分性別</option>
        </select>
        <label for="age-select">年齡層:</label>
        <select name="age" id="age-select" class="w-select">
        <option value="0-100">請選擇年齡階段</option>
          <option value="0-12">兒童(0-12歲)</option>
          <option value="13-18">青年(13-24歲)</option>
          <option value="19-30">壯年(25-44歲)</option>
          <option value="31-50">中年(45-64歲)</option>
          <option value="51-65">老年(65-100歲)</option>
          <option value="0-100">不分年齡</option>
        </select>

        <input type="submit" name="submit" value="查詢" class="w-button">
      </form>
      <div class="container">
        <div class="result-container">
          <?php
          if (isset($_POST['submit'])) {
            $gender = $_POST['gender'];
            $age = $_POST['age'];

            // 資料庫連線
            $conn = mysqli_connect("localhost", "root", "12345678", "icd_test");
            if (!$conn) {
              die("連線失敗: " . mysqli_connect_error());
            }

            // 根據使用者的選擇篩選資料
            // 定義數字範圍
            $age_ranges = [
              '0-12' => [0, 12],
              '13-18' => [13, 18],
              '19-30' => [19, 30],
              '31-50' => [31, 50],
              '51-65' => [51, 65],
              '0-100' => [0, 100],
            ];

            // 取得對應的範圍
            if (isset($age_ranges[$age])) {
              $range = $age_ranges[$age];
              $age_condition = "AGE BETWEEN {$range[0]} AND {$range[1]}";
            } else {
              $age_condition = "1";
            }



            // 構造 SQL 語句
            if ($gender == 'ALL') {
              $sql_icd_count = "SELECT ICD10, COUNT(*) AS count FROM data103 WHERE $age_condition GROUP BY ICD10 ORDER BY count DESC LIMIT 10";
              $sql_class_count = "SELECT CASE
          WHEN TRIM(ICD10) BETWEEN 'A00' AND 'B99' THEN '某些传染病和寄生虫病' 
          WHEN TRIM(ICD10) BETWEEN 'C00' AND 'D48' THEN '肿瘤' 
          WHEN TRIM(ICD10) BETWEEN 'D50' AND 'D89' THEN '血液及造血器官疾病和某些涉及免疫机制的疾患' 
          WHEN TRIM(ICD10) BETWEEN 'E00' AND 'E90' THEN '内分泌、营养和代谢疾病' 
          WHEN TRIM(ICD10) BETWEEN 'F00' AND 'F99' THEN '精神和行为障碍' 
          WHEN TRIM(ICD10) BETWEEN 'G00' AND 'G99' THEN '神经系统疾病' 
          WHEN TRIM(ICD10) BETWEEN 'H00' AND 'H59' THEN '眼和附器疾病' 
          WHEN TRIM(ICD10) BETWEEN 'H60' AND 'H95' THEN '耳和乳突疾病' 
          WHEN TRIM(ICD10) BETWEEN 'I00' AND 'I99' THEN '循环系统疾病' 
          WHEN TRIM(ICD10) BETWEEN 'J00' AND 'J99' THEN '呼吸系统疾病' 
          WHEN TRIM(ICD10) BETWEEN 'K00' AND 'K93' THEN '消化系统疾病' 
          WHEN TRIM(ICD10) BETWEEN 'L00' AND 'L99' THEN '皮肤和皮下组织疾病' 
          WHEN TRIM(ICD10) BETWEEN 'M00' AND 'M99' THEN '肌肉骨骼系统和结缔组织疾病' 
          WHEN TRIM(ICD10) BETWEEN 'N00' AND 'N99' THEN '泌尿生殖系统疾病' 
          WHEN TRIM(ICD10) BETWEEN 'O00' AND 'O99' THEN '妊娠、分娩和产褥期' 
          WHEN TRIM(ICD10) BETWEEN 'P00' AND 'P96' THEN '起源于围生期的某些情况' 
          WHEN TRIM(ICD10) BETWEEN 'Q00' AND 'Q99' THEN '先天畸形、变形和染色体异常'
          WHEN TRIM(ICD10) BETWEEN 'R00' AND 'R99' THEN '症狀、體徵和臨床與實驗室異常所見，不可歸類在他處者' 
          WHEN TRIM(ICD10) BETWEEN 'S00' AND 'T98' THEN '損傷、中毒和外因的某些其他後果' 
          WHEN TRIM(ICD10) BETWEEN 'V01' AND 'Y98' THEN '疾病和死亡的外因' 
          WHEN TRIM(ICD10) BETWEEN 'Z00' AND 'Z99' THEN '影響健康狀態和與保健機構接觸的因素'  
          ELSE '其他' 
            END AS icd_category, COUNT(*) AS count FROM data103 WHERE SEX='F' AND AGE BETWEEN 0 AND 12 GROUP BY icd_category ORDER BY count DESC";

            } else {
              $sql_icd_count = "SELECT ICD10, COUNT(*) AS count FROM data103 WHERE SEX='$gender' AND $age_condition GROUP BY ICD10 ORDER BY count DESC LIMIT 10";
              $sql_class_count = "SELECT CASE
          WHEN TRIM(ICD10) BETWEEN 'A00' AND 'B99' THEN '某些传染病和寄生虫病' 
          WHEN TRIM(ICD10) BETWEEN 'C00' AND 'D48' THEN '肿瘤' 
          WHEN TRIM(ICD10) BETWEEN 'D50' AND 'D89' THEN '血液及造血器官疾病和某些涉及免疫机制的疾患' 
          WHEN TRIM(ICD10) BETWEEN 'E00' AND 'E90' THEN '内分泌、营养和代谢疾病' 
          WHEN TRIM(ICD10) BETWEEN 'F00' AND 'F99' THEN '精神和行为障碍' 
          WHEN TRIM(ICD10) BETWEEN 'G00' AND 'G99' THEN '神经系统疾病' 
          WHEN TRIM(ICD10) BETWEEN 'H00' AND 'H59' THEN '眼和附器疾病' 
          WHEN TRIM(ICD10) BETWEEN 'H60' AND 'H95' THEN '耳和乳突疾病' 
          WHEN TRIM(ICD10) BETWEEN 'I00' AND 'I99' THEN '循环系统疾病' 
          WHEN TRIM(ICD10) BETWEEN 'J00' AND 'J99' THEN '呼吸系统疾病' 
          WHEN TRIM(ICD10) BETWEEN 'K00' AND 'K93' THEN '消化系统疾病' 
          WHEN TRIM(ICD10) BETWEEN 'L00' AND 'L99' THEN '皮肤和皮下组织疾病' 
          WHEN TRIM(ICD10) BETWEEN 'M00' AND 'M99' THEN '肌肉骨骼系统和结缔组织疾病' 
          WHEN TRIM(ICD10) BETWEEN 'N00' AND 'N99' THEN '泌尿生殖系统疾病' 
          WHEN TRIM(ICD10) BETWEEN 'O00' AND 'O99' THEN '妊娠、分娩和产褥期' 
          WHEN TRIM(ICD10) BETWEEN 'P00' AND 'P96' THEN '起源于围生期的某些情况' 
          WHEN TRIM(ICD10) BETWEEN 'Q00' AND 'Q99' THEN '先天畸形、变形和染色体异常'
          WHEN TRIM(ICD10) BETWEEN 'R00' AND 'R99' THEN '症狀、體徵和臨床與實驗室異常所見，不可歸類在他處者' 
          WHEN TRIM(ICD10) BETWEEN 'S00' AND 'T98' THEN '損傷、中毒和外因的某些其他後果' 
          WHEN TRIM(ICD10) BETWEEN 'V01' AND 'Y98' THEN '疾病和死亡的外因' 
          WHEN TRIM(ICD10) BETWEEN 'Z00' AND 'Z99' THEN '影響健康狀態和與保健機構接觸的因素'  
          ELSE '其他' 
            END AS icd_category, COUNT(*) AS count FROM data103 WHERE SEX='F' AND AGE BETWEEN 0 AND 12 GROUP BY icd_category ORDER BY count DESC";
            }
            $result_icd_count = $conn->query($sql_icd_count);
            $result_class_count = $conn->query($sql_class_count);

            // 將結果儲存在陣列中
            $icd10s = array();
            $counts = array();
            $icd_category = array();
            $category_counts = array();
            while ($row = $result_icd_count->fetch_assoc()) {
              $icd10s[] = $row['ICD10'];
              $counts[] = $row['count'];
            }
            while ($row = $result_class_count->fetch_assoc()) {
              $icd_category[] = $row['icd_category'];
              $category_counts[] = $row['count'];
            }

            $data = array(
              'labels' => $icd_category,
              'datasets' => array(
                array(
                  'label' => 'Category Counts',
                  'data' => $category_counts,
                  'backgroundColor' => array(
                    '#ff6384',
                    '#36a2eb',
                    '#cc65fe',
                    '#ffce56',
                    '#4bc0c0',
                    '#ff9f40',
                  ),
                ),
              ),
            );

            // 關閉資料庫連線
            $conn->close();
          } else {
            // 如果沒有提交表單，則初始化陣列
            $icd10s = array();
            $counts = array();
            $icd_category = array();
            $category_counts = array();
          }

          ?>
        </div>

        <!-- 顯示圖表的區域 -->
        <div style="width: 100%; overflow: hidden;">
          <div style="width: 50%; float: left;">
            <canvas id="icd-chart" style="width: 100%; height: 300px;"></canvas>
          </div>
          <div style="width: 50%; float: left;">
            <canvas id="class-chart" style="width: 100%; height: 300px;"></canvas>
          </div>
        </div>

        <script>
          var icdCtx = document.getElementById('icd-chart').getContext('2d');
          var classCtx = document.getElementById('class-chart').getContext('2d');


          var icdChart = new Chart(icdCtx, {
            type: 'bar',
            data: {
              labels: <?php echo json_encode($icd10s); ?>,
              datasets: [{
                label: '疾病數據統計',
                data: <?php echo json_encode($counts); ?>,
                backgroundColor: '#ff6384',
                borderWidth: 1
              }]
            },
            options: {
              scales: {
                yAxes: [{
                  ticks: {
                    beginAtZero: true
                  }
                }]
              },
              title: {
                display: true,
                text: 'ICD10直方圖',
                fontSize: 20
              },
              legend: {
                display: true,
                position: 'bottom'
              }
            }
          });

          var classChart = new Chart(classCtx, {
            type: 'pie',
            data: <?php echo isset($data) ? json_encode($data) : '[]'; ?>,
            options: {
              title: {
                display: true,
                text: 'ICD10圓餅圖',
                fontSize: 20
              },
              legend: {
                display: true,
                position: 'bottom'
              }
            }
          });

        </script>
      </div>

    </div>
  </div>

























  <div class="section footer wf-section">
    <div class="w-container">
      <div class="w-row">
        <div class="w-clearfix w-col w-col-4"><img src="images/logo.png" width="20" alt="" class="stone-logo footer">
          <div class="footer-text">輔大醫學資訊學程 專題研究第二組</div>
        </div>
        <div class="w-col w-col-4">
          <a href="contact.html" class="button beige footer">網站資訊與我們的聯絡</a>
        </div>
        <div class="w-col w-col-4">
          <div class="footer-text address">2022.12</div>
        </div>
      </div>
    </div>
  </div>
  <script src="https://d3e54v103j8qbb.cloudfront.net/js/jquery-3.5.1.min.dc5e7f18c8.js?site=63845ae083c0cd91137a04f3"
    type="text/javascript" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0="
    crossorigin="anonymous"></script>
  <script src="js/webflow.js" type="text/javascript"></script>
  <!-- [if lte IE 9]><script src="https://cdnjs.cloudflare.com/ajax/libs/placeholders/3.0.2/placeholders.min.js"></script><![endif] -->
</body>

</html>