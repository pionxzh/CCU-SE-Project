# Server API

## 註冊登入

| Url       | Request Method | Description           |
| --------- | -------------- | --------------------- |
| /login    | POST           | [登入](#登入)         |
| /register | POST           | [註冊](#註冊)         |
| /resetpwd | POST           | [重設密碼](#重設密碼) |


## API

| Url                | Request Method | Description               |
| ------------------ | -------------- | ------------------------- |
| /api/user          | GET            | [回傳基本登入資訊](#user) |
| /api/user/personal | GET            | [回傳個人資料](#個人資料) |
| /api/user/personal | POST           | [修改個人資料](#個人資料) |


## 履歷表

| Url                | Request Method | Description                             |
| ------------------ | -------------- | --------------------------------------- |
| /resume            | GET            | 用戶個人履歷頁面                        |
| /resume            | POST           | 回傳[履歷資料](#履歷資料)               |
| /resume/basic      | PUT            | [修改履歷-個人資料-基本資料](#基本資料) |
| /resume/background | PUT            | [修改履歷-學歷經驗](#學歷經驗)          |
| /resume/condition  | PUT            | [修改履歷-求職條件](#求職條件)          |
| /resume/skill      | PUT            | [修改履歷-技能與證照](#技能與證照)      |
| /resume/bio        | PUT            | [修改履歷-自傳](#自傳)                  |



## 徵才表

| Url                      | Request Method | Description                |
| ------------------------ | -------------- | -------------------------- |
| api/recruit              | GET            | 回傳所有該廠的徵才訊息     |
| api/recruit              | POST           | 新增一筆新的招募資訊       |
| /recruit/{id}            | GET            | 回傳該筆徵才資訊           |
| /recruit/{id}/field      | POST           | [職缺-工作資訊](#職缺資訊) |
| /recruit/{id}/jobinfo    | POST           | [職缺-工作資訊](#職缺資訊) |
| /recruit/{id}/jobrequire | POST           | [職缺-條件要求](#條件要求) |
| /recruit/{id}/benefits   | POST           | [職缺-公司福利](#公司福利) |
| /recruit/{id}/contact    | POST           | [職缺-聯絡方式](#聯絡方式) |



## JSON內容

#### 範例
```
{
    email: 'mymail@google.com',
    password: 'youcantguessmypwd',
    user_type: 1
}
```

### Server回傳值
若無特殊情況，一律回傳

| Properties | Data Type | Description           |
| ---------- | --------- | --------------------- |
| stat       | int       | 狀態，`0失敗` `1成功` |

### 登入
_**POST**_

| Properties | Data Type | Description               |
| ---------- | --------- | ------------------------- |
| email      | string    | 信箱                      |
| password   | string    | 密碼                      |
| user_type  | int       | 用戶身分，`1學生` `2廠商` |


### 註冊
_**POST**_

| Properties | Data Type | Description               |
| ---------- | --------- | ------------------------- |
| email      | string    | 信箱                      |
| password   | string    | 密碼                      |
| user_type  | int       | 用戶身分，`1學生` `2廠商` |

### 重設密碼
_**POST**_

| Properties | Data Type | Description |
| ---------- | --------- | ----------- |
| email      | string    | 信箱        |


### User **（目前只回傳 user_type、email）**
_**GET**_

只有廠商要回傳`companyStatus`

| Properties    | Data Type | Description                                      |
| ------------- | --------- | ------------------------------------------------ |
| nickname      | string    | `howWordSun`                                     |
| user_type     | int       | 用戶身分，`1學生` `2廠商`                        |
| email         | string    | 信箱                                             |
| emailStatus   | boolean   | 信箱驗證，`false未驗證` `true已認證`             |
| companyStatus | boolean   | (**僅廠商**)廠商驗證，`false未驗證` `true已認證` |


### 個人資料
_**GET**_

回傳個人資料 **（目前是全部資料都傳）**

| Properties    | Data Type | Description                          |
| ------------- | --------- | ------------------------------------ |
| nickname      | string    | `howWordSun`                         |
| user_type     | int       | 用戶身分，`1學生` `2廠商`            |
| gender        | int       | 性別，`1男` `2女` `3第三性`          |
| birthday      | string    | 生日，Ex: `1997/01/01`               |
| email         | string    | 信箱                                 |
| emailStatus   | boolean   | 信箱驗證，`false未驗證` `true已認證` |
| companyStatus | boolean   | 廠商驗證，`false未驗證` `true已認證` |
| phone         | string    | 手機/電話                            |
| address       | string    | 地址: ` 嘉義縣民雄鄉大學路5巷7號`    |
| cName         | string    | (**僅廠商**)公司名稱                 |
| cid           | string    | (**僅廠商**)公司行號                 |

_**POST**_

修改個人資料(上下只有email不一樣，不給改)

| Properties | Data Type | Description                              |
| ---------- | --------- | ---------------------------------------- |
| firstname  | string    | 姓`Sung`                                 |
| lastname   | string    | 名 `Hawa`                                |
| gender     | int       | 性別，`1男` `2女` `3第三性`              |
| birthday   | date      | 生日，Ex: `1997/01/01`                   |
| pid        | string    | 身份證： `A123456789`                    |
| phone      | string    | 手機/電話                                |
| nation     | string    | 國籍: `綿羊國`                           |
| cid        | string    | 公司行號 `0123456789`                    |
| cname      | string    | 公司名稱 `綿羊生小羊股份有限公司`        |
| cphone     | string    | 公司電話 `02-12345678`                   |
| caddress   | string    | 公司地址: `綿羊國綿羊路生小羊街78號87樓` |





### 外籍生基本資料
_**PUT**_

| Properties | Data Type | Description                 |
| ---------- | --------- | --------------------------- |
| firstName  | string    | 英文名                      |
| lastName   | string    | 英文姓                      |
| gender     | int       | 性別，`1男` `2女` `3第三性` |
| birthday   | date      | 生日，Ex: `1997/01/01`      |
| pid        | string    | 身分證，Ex: `A12345679`     |
| nation     | string    | 國籍，[國籍清單](#國籍清單) |
| phone      | string    | 手機                        |


### 廠商基本資料
_**PUT**_

| Properties | Data Type | Description                      |
| ---------- | --------- | -------------------------------- |
| cName      | string    | 公司名稱                         |
| cid        | string    | 公司行號                         |
| phone      | string    | 電話                             |
| address    | string    | 地址: `嘉義縣民雄鄉大學路5巷7號` |

## 履歷

### 履歷資料
_**POST**_

回傳json格式之履歷資料，這部分有點繁雜

| Properties | Data Type | Description |
| ---------- | --------- | ----------- |
| 待補       | string    | lol         |


### 基本資料
_**PUT**_

| Properties | Data Type | Description                       |
| ---------- | --------- | --------------------------------- |
| firstName  | string    | 英文名                            |
| lastName   | string    | 英文姓                            |
| pid        | string    | 身分證，Ex: `A12345679`           |
| gender     | int       | 性別，`1男` `2女` `3第三性`       |
| birthday   | string    | 生日，Ex: `1997/01/01`            |
| nation     | string    | 國籍，[國籍清單](#國籍清單)       |
| email      | string    | 信箱                              |
| cellphone  | string    | 手機                              |
| address    | string    | 地址: ` 嘉義縣民雄鄉大學路5巷7號` |

## 學歷經驗
### 求職資料
_**PUT**_

| Properties   | Data Type | Description                                                 |
| ------------ | --------- | ----------------------------------------------------------- |
| jobStat      | int       | 就業狀態，`0待業中` `1工作中`                               |
| specRole     | boolean   | 身心障礙，`false無` `true有`                                |
| driveLicense | int       | (**選填**)駕照，`1機車駕照` `2普通汽車駕照` `3職業駕駛駕照` |

### 學歷
_**PUT**_

| Properties   | Data Type | Description                                              |
| ------------ | --------- | -------------------------------------------------------- |
| schoolName   | string    | 學校名稱，Ex: `National Chung Cheng University`          |
| schoolRegion | int       | 學校地區，[地區清單](#地區清單)                          |
| degree       | int       | 學歷等級，`1博士 2碩士 3大學`                            |
| majorName    | string    | 科系名稱，`Computer Science and Information Engineering` |
| majorType    | string    | 科系類別，[科系清單](#科系清單)                          |
| startYear    | int       | 就學開始年分，Ex:`2016`                                  |
| startMonth   | int       | 就學開始月份，`0~12`                                     |
| endYear      | int       | 就學結束年分，Ex: `2019`                                 |
| endMonth     | int       | 就學結束月份，`0~12`                                     |
| degreeStatus | int       | 就學狀態，`0畢業` `1肄業` `2就學中`                      |


### 年資
_**PUT**_

| Properties | Data Type | Description                                          |
| ---------- | --------- | ---------------------------------------------------- |
| jobRole    | int       | 職務，`0全職` `1兼職`                                |
| jobCat     | string    | 職業類別，[工作清單](#工作清單)                      |
| jobPeriod  | int       | 年資，`1:1年(含以下)` `2:1~2年` `3:2~3年` `4: 3~4年` |


### 求職條件
_**PUT**_

| Properties      | Data Type | Description                                       |
| --------------- | --------- | ------------------------------------------------- |
| expectedJobName | string    | 希望職務名稱，Ex: `前端網頁工程師`                |
| expectedJobType | int       | 希望工作性質，`0全職` `1兼職`                     |
| SalaryFrom      | int       | 工作薪資 從，Ex: `22000`                          |
| SalaryTo        | int       | 工作薪資 到，Ex: `50000`                          |
| expectedJobDec  | string    | 職務內容描述，Ex: `錢多事少離家近`                |
| expectedJobCat  | string    | 希望職務類別，[工作清單](#工作清單)               |
| expectedJobArea | string    | 希望工作地區，[地區清單](#地區清單)               |
| expectedJobTime | int       | 希望工作時段，`0日班` `1夜班` `2大夜班` `3假日班` |
| SalaryType      | int       | 希望工作待遇，`0面議` `1時薪` `2月薪`             |

## 語言能力
_**PUT**_

| Properties  | Data Type | Description                               |
| ----------- | --------- | ----------------------------------------- |
| langCat     | array     | 語言類別，[語言清單](#語言清單)           |
| langAbility | array     | 語言能力，`0不會` `1略懂` `2中等` `3精通` |

## 技能與證照

### 技能專長
_**PUT**_

| Properties  | Data Type | Description                              |
| ----------- | --------- | ---------------------------------------- |
| tool        | array     | 工具，[工具清單](#工具清單)              |
| toolCustom  | array     | 工具(自定義)，EX: `["槌子", "斧頭"]`     |
| skill       | array     | 技能，[技能清單](#技能清單)              |
| skillCustom | array     | 技能(自定義)，Ex: `["溜溜球", "吹口哨"]` |


### 證照能力
_**PUT**_

| Properties | Data Type | Description                                                    |
| ---------- | --------- | -------------------------------------------------------------- |
| cert       | array     | 證照，[證照清單](#證照清單)                                    |
| certCustom | array     | 證書(自定義)，Ex: `["中等會計", "溜溜球大賽冠軍", "哩來哩來"]` |

### 自傳
_**PUT**_

| Properties    | Data Type | Description            |
| ------------- | --------- | ---------------------- |
| autobiography | string    | 自傳，`我是一隻小小鳥` |

### 職缺資料
_**PUT**_

| Properties     | Data Type | Description                       |
| -------------- | --------- | --------------------------------- |
| JobTitle       | string    | 職缺標題，Ex: `前端網頁工程師`    |
| JobType        | int       | 工作性質，`0全職` `1兼職`         |
| JobCat         | string    | 工作類別，[工作清單](#工作清單)   |
| JobPlace       | string    | 工作地點，[地區清單](#地區清單)   |
| SalaryType     | int       | 工作待遇，`0面議` `1時薪` `2月薪` |
| SalaryFrom     | int       | 工作薪資 從，Ex: `22000`          |
| SalaryTo       | int       | 工作薪資 到，Ex: `50000`          |
| jobRequireLang | int       | 語言條件，[語言清單](#語言清單)   |
