SynologyCloudFlareDDNS
========================

Purpose & Pros
---------------
* A script for Cloudflare DDNS on Synology DSM.
* A Minimum Settings required.
* Uses Cloudflare API v4.

Prerequisites
---------------
* Have a active Zone in Cloudflare. (Your Own Doamin, too)
* Have a A Record.

Installation - Easy way
----------------
1. Connect via SSH. (can be activated in DSM)
2. Execute 
```
sudo curl https://raw.githubusercontent.com/namukcom/SynologyCloudflareDDNS/master/setddns.py | python3 -
```
3. Set up DDNS in DSM (Use your Cloudflare __Global API Key__(can be found in My Profile) as a password)

Installation - Difficult way
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

설치방법 - 쉬운 방법
----------------
1. SSH로 접속합니다. (DSM 설정에서 활성화 할 수 있습니다.)
2. 다음의 명령을 실행합니다.
```
sudo curl https://raw.githubusercontent.com/namukcom/SynologyCloudflareDDNS/master/setddns.py | python3 -
```
3. 시놀로지 DSM에서 DDNS 설정을 합니다. (클라우드플레어 __Global API Key__(클라우드플레어 My Profile메뉴밑에 있음)를 비밀번호로 입력하면 됩니다.)

설치방법 - 어려운 방법
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
