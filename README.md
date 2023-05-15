# 共病性平台操作說明

## 資料庫準備
在檔案中找到以下文件並下載
1. data2014.csv (2014最初資料)-資料太大無法上傳
2. ICD10_rr.csv (rr值關聯表)
3. icd_dict.csv (icd9與icd10字典，包含名稱)
4. patient.csv (使用身分證字號查詢_測試人員)

## 資料庫匯入
資料庫名稱可自行設定，此程式碼預設為"icd_web"
將資料匯入並且設定資料表名稱，以下為本程式碼操作範例
1. data103 請匯入data2014.csv
2. icd10_rr 請匯入ICD10_rr.csv
3. icd10_rr 請匯入icd_dict.csv
4. patient 請匯入patient.csv
<img width="195" alt="image" src="https://github.com/wush1237/Comorbidity-Query-Web-v2/assets/75302187/f5938f70-66bd-4387-ba9a-a55675d7b313">

!注意!
因資料筆數數量過多，建議使用LOAD DATA LOCAL INFILE上傳

## 網站執行
請將以下檔案移動至可執行程式，此範例應用Xampp執行。
index.html
icdCode.php
icdName.php
dataChart.php
contact.html
style.css
