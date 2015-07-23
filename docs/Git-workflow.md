- Форкнуться к себе с репозитория `https://github.com/mentor-icron/yii2-confirmator`
- Склонировать `git clone git@github.com:alexforce2/yii2-confirmator.git`	
- Добавить репозиторий `https://github.com/mentor-icron/yii2-confirmator` в качестве дополнительного удаленного  репозитория с именем `upstream`

***

- Актуализировать ветки дополнительного репозитория `git fetch upstream`
- Переключиться на мастер `git checkout upstream/master`
- Создать ветку `git checkout –b ID-задачи` (см. BagReports)
- Добавить функционал и тесты
- После правок и успешных тестов,  добавить в CHANGELOG.md изменения, например 
Bug #ID-задачи: описание бага 
Enh #ID-задачи: описание новшества
- Закоммитить изменения
- Обновиться с дополнительного  удаленного репозитория `git pull upstream master`
- Запушить свою ветку `git push -u origin ID-задачи`
- Создать Pull Request на основе своей ветки
- Поле успешного принятия (ревью) реквеста можно удалить ветку.
