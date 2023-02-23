# Insurance Recommand
여러 보험사에서 판매중인 보험상품들 중, 개인 건강 데이터를 기반으로 보험사와 보험상품을 알려주고 추천하는 웹, 앱 어플리케이션.

## 개발 기간

- 2022.11.01 ~ 2022.12.13 <br>

    DB 수업 팀 프로젝트로 진행됨.<br>
    데이터베이스는 학교에서 제공된 Oracle 데이터베이스, 서버는 학교 서버 사용.

- 2022.12.18 ~ 2022.01.30<br>

    팀 프로젝트가 끝난 후 개별로 진행함.<br>
    학교 제공 Oracle DB와 서버 대신 Dothome 이라는 웹 호스팅 서비스에서 웹 서버와 DB ( mySQL )를 이용함.<br>
    - Oracle DB에서 mySql로 모든 PHP 코드 변환.
    - 웹 사이트 성능, 디자인, 버그 개선
    - 안드로이드 스튜디오를 사용해 안드로이드 앱으로 개발.

### 개발 환경
- `PHP`
- `CSS`
- `Java Script`
- `Android Studio ( Java )`
- **IDE** : Visual Studio
- **DB** : Oracle, mySQL
- **Web Server** : DotHome


## 동기
### 기존 기술들
- 최근 첨단기술과 의료산업을 결합하여 새로운 의료 서비스를 제공하는 차세대 "스마트 헬스케어" 산업이 큰 주목을 받고 있다.<br>
특히 스마트워치의 이용률이 점차 증가하고 있는 이 추세에서 서울시에서는 '손목닥터 9988' 이라는 스마트 헬스케어 사업을 진행하였으며, 
삼성생명에서는 '와치4U'라는 스마트워치 연계 건강관리 보험을 출시하였다.<br>
국내 뿐만 아니라 해외에서도 Apple Watch 데이터를 건강 기록과 결합하여 개인화된 목표를 만들고 이를 달성 시 보상을 주는 'Attain by Aetna'라는 어플리케이션도 최근 큰 인기다.<br>
- 그러나 이러한 기술들 중 보험을 추천해주는 기술들은 별로 없었으며, 나머지들도 대부분 자회사의 보험만 추천받을 수 있게 되어 있어 사실상 보험을 추천받는 데 제약이 따랐다.<br>

### Insurance Recommand 웹,앱 어플리케이션
- 이러한 스마트 헬스케어 흐름에 맞추어 최근 사용률이 급증한 스마트워치를 통해 얻을 수 있는 건강 정보들로 자신의 건강 상태를 '주의' 단계와 '위험' 단계로 나누어 진단받고,
그 진단 결과와 단계에 따른 보험을 **보험사 제약 없이** 추천해주는 어플리케이션.

## 요구사항
### 기능적 요구사항
- 사용자 개인 정보
  1. 사용자 회원가입 / 로그인
  2. 사용자 정보 조회
  3. 사용자 정보 추가 /수정 / 삭제
- 사용자의 건강 정보
  1. 건강 정보 조회
  2. 건강 정보 추가 / 삭제
  3. 건강 정보 진단
-보험 상품
  1. 보험 상품 조회
  2. 보험 상품 추천

### 비기능적 요구사항
- UI 설계
- 보험사 사이트 링크 연동 
- 추천 받은 보험 찜하기
- 개인 건강 정보 기록 열람
- 타 보험 제품 가격 비교 (가입 가격, 보장 가격)

## 테이블
<img width="550" alt="스크린샷 2023-02-07 오후 11 52 01" src="https://user-images.githubusercontent.com/103736987/217278813-ba0fdb78-e15a-44eb-aa84-fc67971ab749.png">

- 스키마 설명

    - CUSTOMERINFO : 고객 정보

    - HEALTH : 개인 건강 정보

    - REFERENCE : 참조 테이블 ( 고객ID, 보험ID )

    - PRODUCT : 보험 상품

    - INS : 보험사
    
- 관계
    - INS, PRODUCT - 1 : N 관계 - INSID 를 통해 PRODUCT 에서 INS를 참조함.
    - CUSTOMERINFO , HEALTH - 1 : N 관계 - CUSTOMER_ID 를 통해 HEALTH 에서 CUSTOMERINFO를 참조함.
    - CUSTOMERINFO, REFERENCETABLE, INS - M : N 관계 - CUSTOMERINFO 의 CUSTOMER_ID 와 INS 의 INSID 를 통해 M:N 관계를 성립하는 REFERENCETABLE 가 존재한다.
- 정규화
    - 1NF : 테이블의 컬럼이 원자값(Atomic Value, 하나의 값)을 갖도록 테이블이 구성되어 있음.
    - 2NF : 1정규화를 진행한 테이블에 대해서 완전 함수 종속(Full Functional Dependency) 만족.
    - BCNF : INSID & CUSTOMER_ID는 후보키 집합에 속함 - 이상현상(Anomaly) 방지.
- CASCADE
    - REFERENCEABLE – CUSTOMERINFO : ON DELETE CASCADE - 고객이 회원 탈퇴하면 REFERENCETABLE 에서도 삭제됨.
    - HELATH - CUSTOMERINFO : ON DELETE CASCADE - 고객이 회원 탈퇴하면 고객의 건강정보다 삭제됨.

## 보험 추천을 위한 건강 진단 기준

<img width="370" alt="스크린샷 2023-02-08 오전 12 41 42" src="https://user-images.githubusercontent.com/103736987/217291718-057fcbe2-6cb2-4222-a889-7e9810ec0df6.png">    <img width="370" alt="스크린샷 2023-02-08 오전 12 39 43" src="https://user-images.githubusercontent.com/103736987/217291240-95911d1c-1816-4610-845e-18c0dbc200a9.png">
<img width="370" alt="스크린샷 2023-02-08 오전 12 44 06" src="https://user-images.githubusercontent.com/103736987/217292393-2787d55d-7a22-4e78-acbf-f0fb4af18e47.png">  <img width="370" alt="스크린샷 2023-02-08 오전 12 42 42" src="https://user-images.githubusercontent.com/103736987/217292044-94b7aba6-95f6-4f1a-a7ba-93721956ddd3.png">
<img width="370" alt="스크린샷 2023-02-08 오전 12 41 22" src="https://user-images.githubusercontent.com/103736987/217291632-b4ddb30f-1558-4cc8-ad0c-c133488ba9c7.png">  <img width="370" alt="스크린샷 2023-02-08 오전 12 39 30" src="https://user-images.githubusercontent.com/103736987/217291196-df5fdb5a-cd3b-4ba2-95ec-4d7ea6140c83.png">

## 실행 화면

- 웹 ( PHP )<br><br>
    <img width="380" alt="1" src="https://user-images.githubusercontent.com/103736987/217294260-894a8174-fb2b-4e7f-92b4-447181cc6a39.png">
    <img width="380" alt="2" src="https://user-images.githubusercontent.com/103736987/217294356-99001549-3059-46ee-ae4b-cef251a8bfdc.png">
    <img width="380" alt="3" src="https://user-images.githubusercontent.com/103736987/217294531-9b0e4122-9dab-4f28-978b-09bba2369ca6.png">
    <img width="380" alt="10" src="https://user-images.githubusercontent.com/103736987/217294551-cc61cb3f-90c9-4528-946d-354ee239c32e.png">
    <img width="380" alt="11" src="https://user-images.githubusercontent.com/103736987/217294557-4156e615-9549-4117-acbe-e3ee33daba9c.png">
    <img width="380" alt="12" src="https://user-images.githubusercontent.com/103736987/217294568-a5bd0cac-956c-4ea7-89a0-e54a08264307.png">
    <img width="380" alt="15" src="https://user-images.githubusercontent.com/103736987/217294572-cdd9cfbe-bf23-44c1-8ca5-11ccc0c6aa87.png">
    <img width="380" alt="18" src="https://user-images.githubusercontent.com/103736987/217294579-2b0bafae-2b94-4d03-a9c5-0af30e638e0e.png">
    <img width="380" alt="19" src="https://user-images.githubusercontent.com/103736987/217294585-3e122b07-8d40-4be3-947f-3844e4bd5755.png">
    <img width="380" alt="20" src="https://user-images.githubusercontent.com/103736987/217294587-c5c5eb6f-f8eb-499c-ab87-0def9bcb7779.png">

- 앱 ( 안드로이드 )<br><br>
    <img width="190" alt="1" src="https://user-images.githubusercontent.com/103736987/217295432-5cc55fef-21da-4e99-b4d0-b96dfdd28b2a.jpeg">
    <img width="190" alt="2" src="https://user-images.githubusercontent.com/103736987/217295449-233b0870-4159-48a7-88ad-cdae77695f89.jpeg">
    <img width="190" alt="3" src="https://user-images.githubusercontent.com/103736987/217295459-8f1d4ea2-63cf-4394-a173-53ab6b7aadcb.jpeg">
    <img width="190" alt="4" src="https://user-images.githubusercontent.com/103736987/217295472-7bed76f1-cee8-4db4-8b6e-19f2b34a3fd6.jpeg">
    <img width="190" alt="5" src="https://user-images.githubusercontent.com/103736987/217299445-654bdfae-7a71-495e-a419-2d3ae58fdcb9.jpeg">
    <img width="190" alt="6" src="https://user-images.githubusercontent.com/103736987/217299151-c7a1af28-124f-4265-ab39-4cb7527c5a4b.jpeg">
    <img width="190" alt="7" src="https://user-images.githubusercontent.com/103736987/217299172-10b97aee-103f-46d3-84d8-eb2dfcf18872.jpeg">
    <img width="190" alt="8" src="https://user-images.githubusercontent.com/103736987/217299188-3e226070-28fb-408c-8a11-61205afda298.jpeg">
    <img width="190" alt="9" src="https://user-images.githubusercontent.com/103736987/217299193-c8843297-ebbc-48d9-a890-0374eb3b728c.jpeg">
    <img width="190" alt="10" src="https://user-images.githubusercontent.com/103736987/217299198-9a4d6f41-b1b5-4f5b-a630-6632ea778435.jpeg">
    <img width="190" alt="11" src="https://user-images.githubusercontent.com/103736987/217299204-50742416-d672-41a6-a20a-0b5b2f4f78e0.jpeg">
    <img width="190" alt="12" src="https://user-images.githubusercontent.com/103736987/217299209-4b10092f-aa3c-4758-bffd-f982c2848c60.jpeg">
    <img width="190" alt="13" src="https://user-images.githubusercontent.com/103736987/217299219-488f80e6-b703-4ef2-8fca-02231813703f.jpeg">
    <img width="190" alt="14" src="https://user-images.githubusercontent.com/103736987/217299222-d61ffd3f-c6c1-4905-8151-a9d5b3a94850.jpeg">
    <img width="190" alt="15" src="https://user-images.githubusercontent.com/103736987/217299226-7f95e61e-359b-48d9-9d51-dbe6e36191ee.jpeg">
    <img width="190" alt="16" src="https://user-images.githubusercontent.com/103736987/217299230-72eca51e-ad34-4c3f-aa9e-492e64d41439.jpeg">
    <img width="190" alt="17" src="https://user-images.githubusercontent.com/103736987/217299236-e3c17ecf-26bb-4ce1-b7e3-53fb06a6005f.jpeg">
    
## 실행 & 설치
**게스트용 아이디 : 1234, 비밀번호 : 1234**
- 보험 추천 웹사이트 : http://poqop721.dothome.co.kr/insurance/main.php
- 안드로이드 어플리케이션 APK 설치파일 다운로드 : https://drive.google.com/file/d/1OpJ89sXyfLbT9im03plSJ1bjhUxJ-qaw/view
    









    <img width="190" alt="6" src="https://user-images.githubusercontent.com/103736987/217299151-c7a1af28-124f-4265-ab39-4cb7527c5a4b.jpeg">
    <img width="190" alt="7" src="https://user-images.githubusercontent.com/103736987/217299172-10b97aee-103f-46d3-84d8-eb2dfcf18872.jpeg">
    <img width="190" alt="8" src="https://user-images.githubusercontent.com/103736987/217299188-3e226070-28fb-408c-8a11-61205afda298.jpeg">
    <img width="190" alt="9" src="https://user-images.githubusercontent.com/103736987/217299193-c8843297-ebbc-48d9-a890-0374eb3b728c.jpeg">
    <img width="190" alt="10" src="https://user-images.githubusercontent.com/103736987/217299198-9a4d6f41-b1b5-4f5b-a630-6632ea778435.jpeg">
    <img width="190" alt="11" src="https://user-images.githubusercontent.com/103736987/217299204-50742416-d672-41a6-a20a-0b5b2f4f78e0.jpeg">
    <img width="190" alt="12" src="https://user-images.githubusercontent.com/103736987/217299209-4b10092f-aa3c-4758-bffd-f982c2848c60.jpeg">
    <img width="190" alt="13" src="https://user-images.githubusercontent.com/103736987/217299219-488f80e6-b703-4ef2-8fca-02231813703f.jpeg">
    <img width="190" alt="14" src="https://user-images.githubusercontent.com/103736987/217299222-d61ffd3f-c6c1-4905-8151-a9d5b3a94850.jpeg">
    <img width="190" alt="15" src="https://user-images.githubusercontent.com/103736987/217299226-7f95e61e-359b-48d9-9d51-dbe6e36191ee.jpeg">
    <img width="190" alt="16" src="https://user-images.githubusercontent.com/103736987/217299230-72eca51e-ad34-4c3f-aa9e-492e64d41439.jpeg">
    <img width="190" alt="17" src="https://user-images.githubusercontent.com/103736987/217299236-e3c17ecf-26bb-4ce1-b7e3-53fb06a6005f.jpeg">
    
## 실행 & 설치
**게스트용 아이디 : 1234, 비밀번호 : 1234**
- 보험 추천 웹사이트 : http://poqop721.dothome.co.kr/insurance/main.php
- 안드로이드 어플리케이션 APK 설치파일 다운로드 : https://drive.google.com/file/d/1OpJ89sXyfLbT9im03plSJ1bjhUxJ-qaw/view
    














