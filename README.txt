Для розгортання проєкту потрібно мати 
На віндовс
1. Docker Desptop 
2. Ubuntu 20.04.06 LTS  з майнкрософт стор 
3. git
4. ddev

На лінукс 
1. Docker 
2. git 
3. ddev

Щоб розгорнути проєкт потрібно скопіювати наступну команду: 
git clone ssh://codeserver.dev.876c7118-3c3a-4bb2-8536-179e2510dde0@codeserver.dev.876c7118-3c3a-4bb2-8536-179e2510dde0.drush.in:2222/~/repository.git -b master my-pet-web-platform

скачати актуальну базу даних та файли з пантеону:
https://dashboard.pantheon.io/sites/876c7118-3c3a-4bb2-8536-179e2510dde0#live/backups/backup-log

Прописати 
ddev composer install - скачати усі залежності 

ddev start - підняти проєкт 
ddev import-db --file=database.sql.gz - добавити базу даних 
ddev drush cr - почистити зайві збережені речі які можуть аважати
ddev drush uli - згенерувати посилання до адмін доступу на вебсайт
