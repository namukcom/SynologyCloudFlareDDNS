SynologyCloudFlareDDNS
========================

Purpose & Pros
---------------
* A script for Cloudflare DDNS on Synology DSM.
* A Minimum Settings required.
* Uses Cloudflare API v4.

Changelog
---------------
2022.02.14. Supports both "API Tokens" and "Global API Key"

Prerequisites
---------------
* Have a active Zone in Cloudflare. (Your own domain, too)
* Have a A Record.

Installation - Simple way (requires DSM 7.0+ or Python3 installed)
----------------
1. Open **Task Scheduler** (Control Panel - [Services] Task Scheduler)

2. Create a user-defined script item.
    Create - Triggered Task - User-defined script
```
[General Tab]
Task: Cloudflare DDNS (not important)
User: root
Event: Boot-up
Pre-task: none
Enabled: Checked
```
```
[Task Settings Tab]
[Run Command] User-defined script
    curl https://raw.githubusercontent.com/namukcom/SynologyCloudflareDDNS/master/setddns.py | python3 -
```

3. Press OK

4. Right-Click on the task you've just created.

5. Click Run

6. You can see Cloudflare DDNS has been added to your DDNS list.

7. Setup DDNS in Synology DSM (You can use "API Tokens" or "Global API Key")
> 1. Using API Tokens (Recommended)
>    > Single Domain and single permission can granted with a Token -> more secure
>    > How to create: Cloudflare - My Profile - API Tokens - Create Token (Use "Edit zone DNS" template, required permission: Zone - DNS - Edit)
>    > Synology DDNS Settings
>    > ```
>    > Username: Anything you want(not using when authorize the token)
>    > Password: API Token (40 byte)
>    > ```
>
> 2. Using Global API Key
>    > All permission with a single API Key - less secure
>    > How to view: Cloudflare - My Profile - API Tokens - Global API Key - Click "View"
>    > Synology DDNS Settings
>    > ```
>    > Username: Cloudflare Username
>    > Password: Global API Key (37 byte)
>    > ```

Installation - Another way (DSM 7.0- or  Python3 NOT installed)
----------------
1. Connect via SSH. (can be activated in DSM)
2. Execute 
```
sudo curl https://raw.githubusercontent.com/namukcom/SynologyCloudflareDDNS/master/cloudflare.php -o /usr/syno/bin/ddns/cloudflare.php && sudo chmod 755 /usr/syno/bin/ddns/cloudflare.php
```

3. Add some notes to end of DDNS config file. You can use your preferred text-editor. *(sudo vi /etc.defaults/ddns_provider.conf)*
```
[Cloudflare]
  modulepath=/usr/syno/bin/ddns/cloudflare.php
  queryurl=https://www.cloudflare.com/
```
4. Set up DDNS in DSM (Use your Cloudflare __Global API Key__(can be found in My Profile) as a password)



SynologyCloudFlareDDNS (시놀로지에서 Cloudflare를 DDNS로 이용하기)
========================

목적 및 특징
---------------
* 시놀로지DSM에서 클라우드플레어(Cloudflare) DDNS를 이용하기 위한 스크립트임.
* 최소한의 세팅이 필요함.
* 클라우드플레어 v4 API를 사용함.

전제조건
---------------
* 클라우드플레어에 Zone을 등록하여야 함(자기만의 도메인도 필요함)
* 클라우드플레어에 DDNS로 사용할 도메인 레코드가 등록되어 있어야 함.

설치방법 - 쉬운 방법 (DSM 7.0 이상이거나 Python3 설치필요)
----------------
1. 제어판에서 **작업 스케줄러**를 실행합니다.

2. **사용자 정의 스크립트**를 생성합니다.
    생성 - 트리거된 작업 - 사용자 정의 스크립트
```
[일반설정]
작업: Cloudflare DDNS (중요하지않음)
사용자: root
이벤트: 부트업
사전 작업: 없음
활성화됨: 체크
```
```
[작업 설정]
[실행명령] 사용자 정의 스크립트
    curl https://raw.githubusercontent.com/namukcom/SynologyCloudflareDDNS/master/setddns.py | python3 -
```

3. **확인**을 누릅니다.

4. 방금 생성한 작업에 **마우스 우클릭**을 합니다.

5. **실행**을 클릭합니다.

6. DDNS 목록에 **Cloudflare**가 추가된 것을 확인할 수 있습니다.

7. 시놀로지 DSM에서 DDNS 설정을 합니다. (2가지 방식 중 하나 선택)
> 1. API Tokens 사용하기 (권장)
>    > 한개의 토큰으로 특정 도메인 및 권한만 부여하여 사용할 수 있어 유출시에도 다른 도메인이나 권한이 없는 영역에 접근 불가 -> 보안상 이점 가짐
>    > 생성방법: Cloudflare - My Profile - API Tokens - Create Token ("Edit zone DNS" 템플릿 사용, 필요한 권한: Zone - DNS - Edit)
>    > Synology 설정에서 패스워드/키 칸에 생성된 API Token (40 바이트)을 입력하면 됩니다.(사용자 이름/이메일은 아무값이나 입력)
>
> 2. Global API Key 사용하기
>    > 한개의 키로 모든 권한을 가짐 - 보안상 취약
>    > 확인방법: Cloudflare - My Profile - API Tokens - Global API Key - View 클릭
>    > Synology 설정에서 패스워드/키 칸에 확인된 Global API Key (37 바이트)를 입력하면 됩니다.(사용자 이름/이메일은 Cloudflare 계정 입력)


설치방법 - 다른 방법(DSM 7.0 이하이거나 Python3가 설치되지 않은 경우)
----------------
1. SSH로 접속합니다. (DSM 설정에서 활성화 할 수 있습니다.)
2. 다음의 명령을 실행합니다.
```
sudo curl https://raw.githubusercontent.com/namukcom/SynologyCloudflareDDNS/master/cloudflare.php -o /usr/syno/bin/ddns/cloudflare.php && sudo chmod 755 /usr/syno/bin/ddns/cloudflare.php
```

3. DDNS 설정 파일에 다음의 내용을 추가합니다. 마음에 드는 편집기로 수정하시기 바랍니다. *(sudo vi /etc.defaults/ddns_provider.conf)*
```
[Cloudflare]
  modulepath=/usr/syno/bin/ddns/cloudflare.php
  queryurl=https://www.cloudflare.com/
```
4. 시놀로지 DSM에서 DDNS 설정을 합니다. (클라우드플레어 __Global API Key__(클라우드플레어 My Profile메뉴밑에 있음)를 비밀번호로 입력하면 됩니다.)
