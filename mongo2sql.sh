#MongoDB
printf "\nStep 1: MongoDB:"
printf "\nMongo Username: "
read user
printf "\nMongodb password: "
read -s pass
printf "\nMongo Database: "
read db

mongo  "$db" -u "$user" -p "$pass" --eval "load('<javascript/py file name>')"

#Extracting the data from mongo in csv
mongoexport -d "$db" -u "$user" -p "$pass" -c AUTHOR --type=csv --out author.csv --fields _id,lname,fname,email
mongoexport -d "$db" -u "$user" -p "$pass" -c ITEM --type=csv --out itemid.csv --fields _id,price
mongoexport -d "$db" -u "$user" -p "$pass" -c MAGAZINE --type=csv --out magazine.csv --fields _id,name
mongoexport -d "$db" -u "$user" -p "$pass" -c ARTICLE --type=csv --out article.csv --fields article_id,title,pages,v_id,m_id
mongoexport -d "$db" -u "$user" -p "$pass" -c WRITTENBY --type=csv --out written.csv --fields article_id,author_id

#MySQL
printf "Step 2: MySQL:\n\n"
printf "\nMySQL Username: "
read user
printf "\nMySQL password: "
read -s pass
printf "\nMySQL Database: "
read db

#importing the csv data in MySQL table
mysql -u "$user" --password=$pass "$db" -e "load data local infile 'author.csv' into table AUTHOR fields terminated by ',' lines terminated by '\n' IGNORE 1 LINES (_id,lname,fname,email)"
mysql -u "$user" --password=$pass "$db" -e "load data local infile 'itemid.csv' into table ITEM fields terminated by ',' lines terminated by '\n' (_id,price)"
mysql -u "$user" --password=$pass "$db" -e "load data local infile 'magazine.csv' into table MAGAZINE fields terminated by ',' lines terminated by '\n' (_id,name)"
mysql -u "$user" --password=$pass "$db" -e "load data local infile 'article.csv' into table ARTICLE fields terminated by ',' lines terminated by '\n' (article_id,title,pages,v_id,m_id)"
mysql -u "$user" --password=$pass "$db" -e "load data local infile 'written.csv' into table WRITTENBY fields terminated by ',' lines terminated by '\n' (article_id,author_id)"
