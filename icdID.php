<!DOCTYPE html>
<html data-wf-page="63845ae183c0cd81aa7a04f6" data-wf-site="63845ae083c0cd91137a04f3">

<head>
  <meta charset="utf-8">
  <title>ICD共病性查詢</title>
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
        <a href="index.html" aria-current="page" class="nav-link w-nav-link  w--current">首頁</a>
        <a href="icdID.php" class="nav-link w-nav-link ">共病性查詢</a>
        <a href="icdCode.php" class="nav-link w-nav-link ">ICD共病性</a>
        <a href="icdName.php" class="nav-link w-nav-link">ICD對照查詢</a>
        <a href="dataChart.php" class="nav-link w-nav-link ">疾病趨勢圖</a>
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
      <h1>共病性查詢</h1>
      <div class="horizontal-bar beige"></div>
      <div class="subheading">共病性指數查詢系統</div>
    </div>
  </div>


  <div class="section wf-section">
    <script>
      function validateForm() {
        var id = document.forms["myForm"]["id"].value;
        if (id == "") {
          alert("身分證字號不得為空");
          return false;
        } else if (!/^[A-Z][1-2]\d{8}$/.test(id)) {
          alert("身分證字號格式不正確，請重新輸入");
          return false;
        }
      }
    </script>


    <div class="w-container">
      <div class="w-row">
        <div class="w-col w-col-4">
          <div class="number">enter</div>
          <h2 class="project-title">輸入基本資料</h2>



          <div class="w-form">
            <form id="wf-form-icd-from" name="myForm" data-name="icd from" method="POST" action=" "
              onsubmit="return validateForm()"><label for="id">身分證字號</label>
              <input type="text" class="w-input" maxlength="256" name="id" data-name="id" placeholder="" id="id">

              </select>
              <input type="submit" value="查詢" name="submit" data-wait="Please wait..." class="w-button">

            </form>

          </div>
          <div class="existing-icd-container">
            <?php
            if (isset($_POST['submit'])) {
              $id = $_POST['id'];

              // 資料庫連線
              $conn = mysqli_connect("localhost", "root", "12345678", "icd_test");
              if (!$conn) {
                die("連線失敗: " . mysqli_connect_error());
              }

              // 查詢該患者已被診斷的ICD編碼
              $sql_existing = "SELECT ICD FROM tester WHERE CHARTID = '$id'";
              $result_existing = mysqli_query($conn, $sql_existing);
              echo "身分證字號 $id 患者<br>";

              if (mysqli_num_rows($result_existing) > 0) {
                echo "<div class='existing-icd-container'>";
                echo "<h2>已診斷疾病：</h2>";
                echo "<ul>";
                while ($row_existing = mysqli_fetch_assoc($result_existing)) {
                  $icd_existing = $row_existing["ICD"];
                  $icd_existing_name = mysqli_query($conn, "SELECT ICDname FROM icd9toicd10 WHERE TRIM(ICD10code) = '$icd_existing'");
                  $icd_name = "";
                  if (mysqli_num_rows($icd_existing_name) > 0) {
                    $row_icd_name = mysqli_fetch_assoc($icd_existing_name);
                    $icd_name = $row_icd_name["ICDname"];
                  }
                  echo "<li>ICD: $icd_existing, $icd_name</li>";
                }
                echo "</ul>";
                echo "</div>";
              } else {
                echo "該病患尚未被診斷任何疾病";
              }
              mysqli_close($conn);
            }
            ?>
          </div>


          <div class="result-container">
            <?php
            if (isset($_POST['submit'])) {
              // 取得使用者輸入的身分證字號
              $id = $_POST['id'];

              // 資料庫連線
              $conn = mysqli_connect("localhost", "root", "12345678", "icd_test");
              if (!$conn) {
                die("連線失敗: " . mysqli_connect_error());
              }

              // 在表格tester搜尋相同身分證字號的ICD編碼
              $sql = "SELECT * FROM tester WHERE CHARTID = '$id'";
              $result = mysqli_query($conn, $sql);

              if (mysqli_num_rows($result) > 0) {
                // 找到了相同身分證字號的ICD編碼，逐一進行第二次搜尋
                $icd_codes = array();
                $diagnosed_icd_codes = array(); // 存儲已被診斷的ICD編碼
                while ($row = mysqli_fetch_assoc($result)) {
                  $icd = $row["ICD"];
                  $diagnosed_icd_codes[] = $icd; // 將已被診斷的ICD編碼存儲到陣列中
                  // 在表格icd_rr中搜尋該ICD編碼的共病性RR值，但不包含已被診斷的疾病
                  $sql2 = "SELECT * FROM icd10_rr WHERE ICD2 LIKE '%$icd%' AND ICD1 NOT IN ('" . implode("','", $diagnosed_icd_codes) . "') ORDER BY RR DESC LIMIT 3";
                  $result2 = mysqli_query($conn, $sql2);

                  // 將結果存儲到陣列中
                  while ($row2 = $result2->fetch_assoc()) {
                    $icd_code = $row2["ICD1"];
                    $rr = $row2["RR"];
                    if (isset($icd_codes[$icd_code])) {
                      // 如果該ICD碼已經存在於$icd_codes中，取出目前的RR值比較
                      if ($icd_codes[$icd_code] < $rr) {
                        $icd_codes[$icd_code] = $rr; // 更新RR值
                      }
                    } else {
                      // 如果該ICD碼不存在於$icd_codes中，直接加入
                      $icd_codes[$icd_code] = $rr;
                    }
                  }
                }

                // 顯示診斷結果
                echo '<div class="result-container">';
                echo '<h2>診斷結果：</h2>';
                if (count($icd_codes) > 0) {
                  arsort($icd_codes); // 將陣列按照 $rr 值由大到小排序
                  echo "<ul>";
                  foreach ($icd_codes as $icd => $rr) {
                    $icd_name = mysqli_query($conn, "SELECT ICDname FROM icd9toicd10 WHERE ICD10code = '$icd'");
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
                  $conn->close();
                  echo "該病患查無相關共病性資料";
                }
              }
            }
            ?>
          </div>





        </div>
        <div class="project-column w-col w-col-8"><img src="images/color1.jpg"
            sizes="(max-width: 767px) 92vw, (max-width: 991px) 63vw, 620px"
            srcset="images/color1-p-500.jpg 500w, images/color1-p-800.jpg 800w, images/color1-p-1080.jpg 1080w, images/color1-p-1600.jpg 1600w, images/color1.jpg 1841w"
            alt=""></div>
      </div>
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