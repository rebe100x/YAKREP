#!/bin/bash
lynx -dump http://ec2-54-247-18-97.eu-west-1.compute.amazonaws.com/PREPROD/YAKREP/BATCH/DB2Ontology.php > /home/bitnami/stack/apache2/htdocs/LOG/PREPROD/ontoBuilder.log
cp /home/bitnami/stack/apache2/htdocs/PREPROD/YAKREP/BATCH/output/yakdicotitle.xml /home/bitnami/stack/exalead/cloudview-V6R2013.SP2.44163-linux-x64/resource/all-arch/yakwala/ontologies/yakdicotitle.xml
/home/bitnami/stack/exalead/theindex/bin/cvadmin linguistic compile-ontology input="/home/bitnami/stack/exalead/cloudview-V6R2013.SP2.44163-linux-x64/resource/all-arch/yakwala/ontologies/yakdicotitle.xml" output="/home/bitnami/stack/exalead/cloudview-V6R2013.SP2.44163-linux-x64/resource/all-arch/yakwala/dico/yakdicotitle/"
