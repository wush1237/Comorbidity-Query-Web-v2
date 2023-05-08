<!DOCTYPE html>
<html data-wf-page="63901a0b07672b71e9df2a4b" data-wf-site="63845ae083c0cd91137a04f3">

<head>
  <meta charset="utf-8">
  <title>ICD中文名稱查詢</title>
  <meta
    content="Stone Template is an awesome dark and beige muted theme for an agency or any other professional service."
    name="description">
  <meta content="ICD" property="og:title">
  <meta content="ICD" property="twitter:title">
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
  <link href="images/favicon.ico" rel="shortcut icon" type="image/x-icon">
  <link href="images/webclip.png" rel="apple-touch-icon">
</head>

<body>
  <div data-collapse="medium" data-animation="default" data-duration="400" data-easing="ease" data-easing2="ease"
    role="banner" class="navbar w-nav">
    <div class="w-container">
      <a href="index.html" class="brand w-clearfix w-nav-brand"><img src="images/logo.png" width="30" alt=""
          class="stone-logo">
        <div class="logo-text">第二組</div>
      </a>
      <nav role="navigation" class="nav-menu w-nav-menu">
        <a href="index.html" class="nav-link w-nav-link ">首頁</a>
        <a href="icdID.php" class="nav-link w-nav-link">共病性查詢</a>
        <a href="icdCode.php" class="nav-link w-nav-link">ICD共病性</a>
        <a href="icdName.php" aria-current="page" class="nav-link w-nav-link w--current ">ICD對照查詢</a>
        <a href="dataChart.php" class="nav-link w-nav-link">疾病趨勢圖</a>
        <a href="contact.html" class="nav-link w-nav-link ">關於我們</a>
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
      <h1>icd疾病編碼</h1>
      <div class="horizontal-bar beige"></div>
      <div class="subheading">編碼中文名稱查詢</div>
    </div>
  </div>
  <div class="section wf-section">
    <div class="w-container">
      <div class="w-row">
        <div class="w-col w-col-4">
          <div class="number">enter</div>
          <h2 class="project-title">輸入ICD編碼</h2>
          <div class="w-form">
            <form id="wf-form-icd-from" name="myForm" data-name="icd from" method="POST" action=" ">
              <label for="keyword">輸入關鍵字：</label>
              <input type="text" class="w-input" maxlength="256" name="keyword">
              <label for="icd9">輸入ICD9：</label>
              <input type="text" class="w-input" maxlength="256" name="icd9">
              <label for="icd10">輸入ICD10：</label>
              <input type="text" class="w-input" maxlength="256" name="icd10">
              <br>
              <input type="submit" value="查詢" name="submit" data-wait="Please wait..." class="w-button">
            </form>
          </div>

          <div class="existing-icd-container">
            <?php
            // 資料庫連線
            $conn = mysqli_connect("localhost", "root", "12345678", "icd_test");
            if (!$conn) {
              die("連線失敗: " . mysqli_connect_error());
            }

            if (isset($_POST['submit'])) {
              // 取得使用者輸入的關鍵字
              if (isset($_POST['keyword'])) {
                $keyword = $_POST['keyword'];
              } else {
                $keyword = '';
              }

              // 取得使用者輸入的ICD9
              if (isset($_POST['icd9'])) {
                $icd9 = $_POST['icd9'];
              } else {
                $icd9 = '';
              }

              // 取得使用者輸入的ICD10
              if (isset($_POST['icd10'])) {
                $icd10 = $_POST['icd10'];
              } else {
                $icd10 = '';
              }

              // 建立SQL查詢語句
              if (!empty($icd9)) {
                $sql = "SELECT * FROM icd9toicd10 WHERE ICD9code='$icd9'";
              } else
                if (!empty($icd10)) {
                  $sql = "SELECT * FROM icd9toicd10 WHERE ICD10code LIKE '%$icd10%'";
                } else {
                  $sql = "SELECT * FROM icd9toicd10 WHERE ICDname LIKE '%$keyword%'";
                }
              $result = $conn->query($sql);

              // 判斷查詢結果是否存在
              if ($result->num_rows > 0) {
                echo "<ul>";
                while ($row = $result->fetch_assoc()) {
                  echo "<li> ICD9: " . $row["ICD9code"] ."</li>";
                  echo "<li>ICD10:" .$row["ICD10code"] ."</li>";
                  echo "<li>Name:" .$row["ICDname"] ."</li>";
                  echo "<br>";
                }
                echo "</ul>";
              } else {
                echo "查無符合條件的資料";
              }
            }




            $conn->close();
            ?>
          </div>

        </div>
        <div class="project-column w-col w-col-8"><img src="images/共病性介紹_1.png"
            sizes="(max-width: 767px) 92vw, (max-width: 991px) 63vw, 620px"
            srcset="images/共病性介紹_1-p-500.png 500w, images/共病性介紹_1-p-800.png 800w, images/共病性介紹_1-p-1080.png 1080w"
            alt=""></div>
      </div>
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