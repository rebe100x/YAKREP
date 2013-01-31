#!/bin/bash
#ZONE1 YAKDICO
lynx -dump http://ec2-54-246-84-102.eu-west-1.compute.amazonaws.com/PREPROD/YAKREP/BATCH/DB2OntologyFull.php > /home/bitnami/stack/nginx/html/PREPROD/LOG/ontoBuilderFull.log
cp -R /home/bitnami/stack/nginx/html/PREPROD/YAKREP/BATCH/output/ontologies/ /home/bitnami/stack/exalead/cloudview-V6R2013.SP2.44163-linux-x64/resource/all-arch/yakwala/
cd /home/bitnami/stack/exalead/cloudview-V6R2013.SP2.44163-linux-x64/resource/all-arch/yakwala/ontologies/
for fi in `ls *.xml`
do
	echo $fi > /home/bitnami/stack/nginx/html/PREPROD/LOG/ontoBuilderFull.log
	fo=${fi%\.*};
	/home/bitnami/stack/exalead/theindex/bin/cvadmin linguistic compile-ontology input="/home/bitnami/stack/exalead/cloudview-V6R2013.SP2.44163-linux-x64/resource/all-arch/yakwala/ontologies/$fi" output="/home/bitnami/stack/exalead/cloudview-V6R2013.SP2.44163-linux-x64/resource/all-arch/yakwala/dico/$fo/" > /home/bitnami/stack/nginx/html/PREPROD/LOG/ontoBuilderFull.log
done

