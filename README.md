��������� �����������:
.docker - ����� � ����������
projects/Task - ����� � ������� �������
docker-compose.yml - ������������ Docker
dump.sql - ���� �� MySQL �������



������ �������. �� ��������� � ����� ������� (�����):
��� �������������� ��������� ���������
docker-compose build
docker-compose up -d

����� � ����� � �������� ��������� composer install
docker exec -it php-5.4 bash
cd /srv/projects/Task/
composer install

����� ����������� ���� �� (MYSQL_ROOT_PASSWORD ������ � ����� docker-compose.yml)
docker exec -it php-5.4 bash (�� ���������, ���� �� �� ����� �� ����������)
mysql -uroot -hdb -pMYSQL_ROOT_PASSWORD
create database task_test;
exit
mysql -uroot -hdb -pMYSQL_ROOT_PASSWORD task_test < /var/www/dump.sql

� ����� /etc/hosts �������� ������
127.0.0.1   task.localhost

��� �������� �������������� ������� �� �������� �� ������ http://task.localhost �������� Hello World!

����� ��� �������� � CMS http://task.localhost/cc �����/������ test/test