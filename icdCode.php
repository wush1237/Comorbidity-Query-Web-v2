<!DOCTYPE html>

<html data-wf-page="63845ae183c0cd81aa7a04f6" data-wf-site="63845ae083c0cd91137a04f3">

<head>
  <meta charset="utf-8">
  <!-- 頁面書籤的標題 -->
  <title>ICD的共病性指數</title>
  <!-- 描述整個網頁 -->
  <meta content="這是一個查詢疾病共病性的平台" name="description">
  <!-- width=device-width 表示設定網頁寬度與螢幕裝置的寬度相同，而 initial-scale=1 則表示初始縮放比例為 1，也就是不進行縮放。 -->
  <meta content="width=device-width, initial-scale=1" name="viewport">

  <link href="css/normalize.css" rel="stylesheet" type="text/css">
  <link href="css/webflow.css" rel="stylesheet" type="text/css">
  <link href="css/comorbiditymiia.webflow.css" rel="stylesheet" type="text/css">
  <script src="https://ajax.googleapis.com/ajax/libs/webfont/1.6.26/webfont.js" type="text/javascript"></script>
  <script
    type="text/javascript">WebFont.load({ google: { families: ["Vollkorn:400,400italic,700,700italic", "Roboto Condensed:300,regular,700", "Roboto:300,regular,500"] } });</script>
  <script
    type="text/javascript">!function (o, c) { var n = c.documentElement, t = " w-mod-"; n.className += t + "js", ("ontouchstart" in o || o.DocumentTouch && c instanceof DocumentTouch) && (n.className += t + "touch") }(window, document);</script>
  <link href="images/favicon.ico" rel="shortcut icon" type="image/x-icon">
  <link href="images/webclip.png" rel="apple-touch-icon">
</head>

<body>
  <div data-collapse="medium" data-animation="default" data-duration="400" data-easing="ease" data-easing2="ease"
    role="banner" class="navbar w-nav">
    <div class="w-container">
      <a href="index.html" class="brand w-clearfix w-nav-brand"><img src="images/logo.png" width="30" alt=""
          class="stone-logo">
        <div class="logo-text">共病性查詢平台</div>
      </a>
      <nav role="navigation" class="nav-menu w-nav-menu">
        <a href="index.html" class="nav-link w-nav-link">首頁</a>
        <a href="icdID.php" class="nav-link w-nav-link">共病性查詢</a>
        <a href="icdCode.php" aria-current="page" class="nav-link w-nav-link w--current">ICD共病性</a>
        <a href="icdName.php" class="nav-link w-nav-link">ICD對照查詢</a>
        <a href="dataChart.php" class="nav-link w-nav-link">疾病趨勢圖</a>
        <a href="contact.html" class="nav-link w-nav-link">關於我們</a>
        <!-- <a href="" aria-current="page" class="nav-link social-icons w-hidden-medium w-hidden-small w-hidden-tiny w-inline-block w-nav-link"><img src="images/icons8-privacy-64-1.png" width="25" alt=""></a> -->

      </nav>

      <div class="menu-button w-nav-button">
        <div class="menu-icon w-icon-nav-menu"></div>
      </div>
    </div>
  </div>
  <div class="section grey wf-section">
    <div class="w-container">
      <h1>查詢疾病間的共病性</h1>
      <div class="horizontal-bar beige"></div>
      <div class="subheading">輸入兩個不同的疾病之ICD進行查詢</div>
    </div>
  </div>
  <div class="section wf-section">
    <div class="w-container">
      <div class="w-row">
        <div class="w-col w-col-4">
          <div class="number">enter</div>
          <h2 class="project-title">輸入疾病ICD</h2>
          <div class="w-form">
            <form id="wf-form-icd-from" name="myForm" data-name="icd from" method="POST" action=" ">
              <label for="icd1">輸入ICD編碼1：</label>
              <input type="text" class="w-input" maxlength="256" name="icd1" required>
              <label for="icd2">輸入ICD編碼2：</label>
              <input type="text" class="w-input" maxlength="256" name="icd2">
              <label for="rows">顯示行數：</label>
              <select name="rows" id="rows" class="w-select">
                <option value="3">3筆</option>
                <option value="4">4筆</option>
                <option value="5">5筆</option>
                <option value="6">6筆</option>
                <option value="7">7筆</option>
                <option value="8">8筆</option>
                <option value="9">9筆</option>
                <option value="10">10筆</option>
                <option value="15">15筆</option>
                <option value="20">20筆</option>
              </select>
              <br>
              <input type="submit" value="查詢" name="submit" data-wait="Please wait..." class="w-button">
            </form>
          </div>


          <div class="result-container">
            <br>
            <h2>搜索結果：</h2>

            <?php
            if (isset($_POST['submit'])) {
              // 取得使用者輸入的 ICD 編碼
              $icd1 = $_POST['icd1'];
              $icd2 = $_POST['icd2'];
              $rows = $_POST['rows'];

              // 資料庫連線
              $conn = mysqli_connect("localhost", "root", "12345678", "icd_web");
              if (!$conn) {
                die("連線失敗: " . mysqli_connect_error());
              }

              if (!empty($icd1) && empty($icd2)) {
                echo "<h3>ICD編碼: $icd1</h3>";

                // 查詢icd共病性資料
                $sql = "SELECT * FROM icd10_rr WHERE ICD2 LIKE '%$icd1%' ORDER BY RR DESC LIMIT $rows";
                $result = $conn->query($sql);

                // 顯示查詢結果
                if ($result->num_rows > 0) {
                  echo "<ul>";
                  while ($row = $result->fetch_assoc()) {
                    $icd = $row["ICD1"];
                    $rr = $row["RR"];
                    $icd_name = mysqli_query($conn, "SELECT ICDname FROM icd_dict WHERE ICD10code = '$icd'");
                    $name = "";
                    if (mysqli_num_rows($icd_name) > 0) {
                      $row_name = mysqli_fetch_assoc($icd_name);
                      $name = $row_name["ICDname"];
                    }
                    echo "<li> ICD: $icd, $name, RR: $rr</li>";
                    echo "<br>";
                  }
                  echo "</ul>";
                } else {
                  echo "查無符合條件的資料";
                }
              }
            }

            // 如果兩個欄位都填，則進行相互的共病性分析
            if (!empty($icd1) && !empty($icd2)) {
              echo "<h3>$icd1,與 $icd2 的共病性關係 <br> </h3>";

              // 查詢icd共病性資料
              $sql = "SELECT * FROM icd10_rr WHERE ICD1 LIKE '%$icd2%' AND ICD2  LIKE '%$icd1%'";
              $result = $conn->query($sql);

              // 顯示查詢結果
              if ($result->num_rows > 0) {
                echo "<ul>";
                $row = $result->fetch_assoc();
                $icd1 = $row["ICD1"];
                $icd2 = $row["ICD2"];
                $rr = $row["RR"];
                $icd_name1 = mysqli_query($conn, "SELECT ICDname FROM icd_dict WHERE ICD10code = '$icd1'");
                $icd_name2 = mysqli_query($conn, "SELECT ICDname FROM icd_dict WHERE ICD10code = '$icd2'");
                $name1 = "";
                $name2 = "";
                if (mysqli_num_rows($icd_name1) > 0) {
                  $row_name1 = mysqli_fetch_assoc($icd_name1);
                  $name1 = $row_name1["ICDname"];
                }
                if (mysqli_num_rows($icd_name2) > 0) {
                  $row_name2 = mysqli_fetch_assoc($icd_name2);
                  $name2 = $row_name2["ICDname"];
                }
                echo "<li> ICD1: $icd2, $name2 </li>";
                echo "<li>ICD2: $icd1, $name1 </li>";
                echo "<li>RR: $rr</li>";
                echo "</ul>";
              } else {
                echo "查無符合條件的資料";
              }
            }
            ?>
          </div>

        </div>
      </div>
      <div class="project-column w-col w-col-8"><img src="images/color1.jpg"
          sizes="(max-width: 767px) 92vw, (max-width: 991px) 63vw, 620px"
          srcset="images/color1-p-500.jpg 500w, images/color1-p-800.jpg 800w, images/color1-p-1080.jpg 1080w, images/color1-p-1600.jpg 1600w, images/color1.jpg 1841w"
          alt=""></div>
    </div>
  </div>

  <div class="section lightgrey wf-section">
    <div class="w-container"></div>
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
          <div class="footer-text address">2023.06</div>
        </div>
      </div>
    </div>
  </div>
  <script src="https://d3e54v103j8qbb.cloudfront.net/js/jquery-3.5.1.min.dc5e7f18c8.js?site=63845ae083c0cd91137a04f3"
    type="text/javascript" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0="
    crossorigin="anonymous"></script>
  <script src="js/webflow.js" type="text/javascript"></script>
  <!-- [if lte IE 9]><script src="https://cdnjs.cloudflare.com/ajax/libs/placeholders/3.0.2/placeholders.min.js"></script><![endif] -->

  </div>
</body>

</html>
</body>