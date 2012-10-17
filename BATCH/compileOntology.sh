#!/bin/bash
#ZONE1
lynx -dump http://ec2-54-247-18-97.eu-west-1.compute.amazonaws.com/PREPROD/YAKREP/BATCH/DB2Ontology.php?zone=1 > /home/bitnami/stack/apache2/htdocs/LOG/PREPROD/ontoBuilder_zone1.log
cp /home/bitnami/stack/apache2/htdocs/PREPROD/YAKREP/BATCH/output/yakdicotitle_zone1.xml /home/bitnami/stack/exalead/cloudview-V6R2013.SP2.44163-linux-x64/resource/all-arch/yakwala/ontologies/yakdicotitle_zone1.xml
cp /home/bitnami/stack/apache2/htdocs/PREPROD/YAKREP/BATCH/output/yakdicotext_zone1.xml /home/bitnami/stack/exalead/cloudview-V6R2013.SP2.44163-linux-x64/resource/all-arch/yakwala/ontologies/yakdicotext_zone1.xml
/home/bitnami/stack/exalead/theindex/bin/cvadmin linguistic compile-ontology input="/home/bitnami/stack/exalead/cloudview-V6R2013.SP2.44163-linux-x64/resource/all-arch/yakwala/ontologies/yakdicotitle_zone1.xml" output="/home/bitnami/stack/exalead/cloudview-V6R2013.SP2.44163-linux-x64/resource/all-arch/yakwala/dico/yakdicotitle_zone1/" > /home/bitnami/stack/apache2/htdocs/LOG/PREPROD/ontoBuilder_zone1.log
/home/bitnami/stack/exalead/theindex/bin/cvadmin linguistic compile-ontology input="/home/bitnami/stack/exalead/cloudview-V6R2013.SP2.44163-linux-x64/resource/all-arch/yakwala/ontologies/yakdicotext_zone1.xml" output="/home/bitnami/stack/exalead/cloudview-V6R2013.SP2.44163-linux-x64/resource/all-arch/yakwala/dico/yakdicotext_zone1/" > /home/bitnami/stack/apache2/htdocs/LOG/PREPROD/ontoBuilder_zone1.log

#ZONE2
lynx -dump http://ec2-54-247-18-97.eu-west-1.compute.amazonaws.com/PREPROD/YAKREP/BATCH/DB2Ontology.php?zone=2 > /home/bitnami/stack/apache2/htdocs/LOG/PREPROD/ontoBuilder_zone2.log
cp /home/bitnami/stack/apache2/htdocs/PREPROD/YAKREP/BATCH/output/yakdicotitle_zone2.xml /home/bitnami/stack/exalead/cloudview-V6R2013.SP2.44163-linux-x64/resource/all-arch/yakwala/ontologies/yakdicotitle_zone2.xml
cp /home/bitnami/stack/apache2/htdocs/PREPROD/YAKREP/BATCH/output/yakdicotext_zone2.xml /home/bitnami/stack/exalead/cloudview-V6R2013.SP2.44163-linux-x64/resource/all-arch/yakwala/ontologies/yakdicotext_zone2.xml
/home/bitnami/stack/exalead/theindex/bin/cvadmin linguistic compile-ontology input="/home/bitnami/stack/exalead/cloudview-V6R2013.SP2.44163-linux-x64/resource/all-arch/yakwala/ontologies/yakdicotitle_zone2.xml" output="/home/bitnami/stack/exalead/cloudview-V6R2013.SP2.44163-linux-x64/resource/all-arch/yakwala/dico/yakdicotitle_zone2/" > /home/bitnami/stack/apache2/htdocs/LOG/PREPROD/ontoBuilder_zone2.log
/home/bitnami/stack/exalead/theindex/bin/cvadmin linguistic compile-ontology input="/home/bitnami/stack/exalead/cloudview-V6R2013.SP2.44163-linux-x64/resource/all-arch/yakwala/ontologies/yakdicotext_zone2.xml" output="/home/bitnami/stack/exalead/cloudview-V6R2013.SP2.44163-linux-x64/resource/all-arch/yakwala/dico/yakdicotext_zone2/" > /home/bitnami/stack/apache2/htdocs/LOG/PREPROD/ontoBuilder_zone2.log

#ZONE3
lynx -dump http://ec2-54-247-18-97.eu-west-1.compute.amazonaws.com/PREPROD/YAKREP/BATCH/DB2Ontology.php?zone=2 > /home/bitnami/stack/apache2/htdocs/LOG/PREPROD/ontoBuilder_zone3.log
cp /home/bitnami/stack/apache2/htdocs/PREPROD/YAKREP/BATCH/output/yakdicotitle_zone3.xml /home/bitnami/stack/exalead/cloudview-V6R2013.SP2.44163-linux-x64/resource/all-arch/yakwala/ontologies/yakdicotitle_zone3.xml
cp /home/bitnami/stack/apache2/htdocs/PREPROD/YAKREP/BATCH/output/yakdicotext_zone3.xml /home/bitnami/stack/exalead/cloudview-V6R2013.SP2.44163-linux-x64/resource/all-arch/yakwala/ontologies/yakdicotext_zone3.xml
/home/bitnami/stack/exalead/theindex/bin/cvadmin linguistic compile-ontology input="/home/bitnami/stack/exalead/cloudview-V6R2013.SP2.44163-linux-x64/resource/all-arch/yakwala/ontologies/yakdicotitle_zone3.xml" output="/home/bitnami/stack/exalead/cloudview-V6R2013.SP2.44163-linux-x64/resource/all-arch/yakwala/dico/yakdicotitle_zone3/" > /home/bitnami/stack/apache2/htdocs/LOG/PREPROD/ontoBuilder_zone3.log
/home/bitnami/stack/exalead/theindex/bin/cvadmin linguistic compile-ontology input="/home/bitnami/stack/exalead/cloudview-V6R2013.SP2.44163-linux-x64/resource/all-arch/yakwala/ontologies/yakdicotext_zone3.xml" output="/home/bitnami/stack/exalead/cloudview-V6R2013.SP2.44163-linux-x64/resource/all-arch/yakwala/dico/yakdicotext_zone3/" > /home/bitnami/stack/apache2/htdocs/LOG/PREPROD/ontoBuilder_zone3.log

#ZONE4
lynx -dump http://ec2-54-247-18-97.eu-west-1.compute.amazonaws.com/PREPROD/YAKREP/BATCH/DB2Ontology.php?zone=2 > /home/bitnami/stack/apache2/htdocs/LOG/PREPROD/ontoBuilder_zone4.log
cp /home/bitnami/stack/apache2/htdocs/PREPROD/YAKREP/BATCH/output/yakdicotitle_zone4.xml /home/bitnami/stack/exalead/cloudview-V6R2013.SP2.44163-linux-x64/resource/all-arch/yakwala/ontologies/yakdicotitle_zone4.xml
cp /home/bitnami/stack/apache2/htdocs/PREPROD/YAKREP/BATCH/output/yakdicotext_zone4.xml /home/bitnami/stack/exalead/cloudview-V6R2013.SP2.44163-linux-x64/resource/all-arch/yakwala/ontologies/yakdicotext_zone4.xml
/home/bitnami/stack/exalead/theindex/bin/cvadmin linguistic compile-ontology input="/home/bitnami/stack/exalead/cloudview-V6R2013.SP2.44163-linux-x64/resource/all-arch/yakwala/ontologies/yakdicotitle_zone4.xml" output="/home/bitnami/stack/exalead/cloudview-V6R2013.SP2.44163-linux-x64/resource/all-arch/yakwala/dico/yakdicotitle_zone4/" > /home/bitnami/stack/apache2/htdocs/LOG/PREPROD/ontoBuilder_zone4.log
/home/bitnami/stack/exalead/theindex/bin/cvadmin linguistic compile-ontology input="/home/bitnami/stack/exalead/cloudview-V6R2013.SP2.44163-linux-x64/resource/all-arch/yakwala/ontologies/yakdicotext_zone4.xml" output="/home/bitnami/stack/exalead/cloudview-V6R2013.SP2.44163-linux-x64/resource/all-arch/yakwala/dico/yakdicotext_zone4/" > /home/bitnami/stack/apache2/htdocs/LOG/PREPROD/ontoBuilder_zone4.log


#ZONE5
lynx -dump http://ec2-54-247-18-97.eu-west-1.compute.amazonaws.com/PREPROD/YAKREP/BATCH/DB2Ontology.php?zone=5 > /home/bitnami/stack/apache2/htdocs/LOG/PREPROD/ontoBuilder_zone5.log
cp /home/bitnami/stack/apache2/htdocs/PREPROD/YAKREP/BATCH/output/yakdicotitle_zone5.xml /home/bitnami/stack/exalead/cloudview-V6R2013.SP2.44163-linux-x64/resource/all-arch/yakwala/ontologies/yakdicotitle_zone5.xml
cp /home/bitnami/stack/apache2/htdocs/PREPROD/YAKREP/BATCH/output/yakdicotext_zone5.xml /home/bitnami/stack/exalead/cloudview-V6R2013.SP2.44163-linux-x64/resource/all-arch/yakwala/ontologies/yakdicotext_zone5.xml
/home/bitnami/stack/exalead/theindex/bin/cvadmin linguistic compile-ontology input="/home/bitnami/stack/exalead/cloudview-V6R2013.SP2.44163-linux-x64/resource/all-arch/yakwala/ontologies/yakdicotitle_zone5.xml" output="/home/bitnami/stack/exalead/cloudview-V6R2013.SP2.44163-linux-x64/resource/all-arch/yakwala/dico/yakdicotitle_zone5/" > /home/bitnami/stack/apache2/htdocs/LOG/PREPROD/ontoBuilder_zone5.log
/home/bitnami/stack/exalead/theindex/bin/cvadmin linguistic compile-ontology input="/home/bitnami/stack/exalead/cloudview-V6R2013.SP2.44163-linux-x64/resource/all-arch/yakwala/ontologies/yakdicotext_zone5.xml" output="/home/bitnami/stack/exalead/cloudview-V6R2013.SP2.44163-linux-x64/resource/all-arch/yakwala/dico/yakdicotext_zone5/" > /home/bitnami/stack/apache2/htdocs/LOG/PREPROD/ontoBuilder_zone5.log

#ZONE6
lynx -dump http://ec2-54-247-18-97.eu-west-1.compute.amazonaws.com/PREPROD/YAKREP/BATCH/DB2Ontology.php?zone=2 > /home/bitnami/stack/apache2/htdocs/LOG/PREPROD/ontoBuilder_zone6.log
cp /home/bitnami/stack/apache2/htdocs/PREPROD/YAKREP/BATCH/output/yakdicotitle_zone6.xml /home/bitnami/stack/exalead/cloudview-V6R2013.SP2.44163-linux-x64/resource/all-arch/yakwala/ontologies/yakdicotitle_zone6.xml
cp /home/bitnami/stack/apache2/htdocs/PREPROD/YAKREP/BATCH/output/yakdicotext_zone6.xml /home/bitnami/stack/exalead/cloudview-V6R2013.SP2.44163-linux-x64/resource/all-arch/yakwala/ontologies/yakdicotext_zone6.xml
/home/bitnami/stack/exalead/theindex/bin/cvadmin linguistic compile-ontology input="/home/bitnami/stack/exalead/cloudview-V6R2013.SP2.44163-linux-x64/resource/all-arch/yakwala/ontologies/yakdicotitle_zone6.xml" output="/home/bitnami/stack/exalead/cloudview-V6R2013.SP2.44163-linux-x64/resource/all-arch/yakwala/dico/yakdicotitle_zone6/" > /home/bitnami/stack/apache2/htdocs/LOG/PREPROD/ontoBuilder_zone6.log
/home/bitnami/stack/exalead/theindex/bin/cvadmin linguistic compile-ontology input="/home/bitnami/stack/exalead/cloudview-V6R2013.SP2.44163-linux-x64/resource/all-arch/yakwala/ontologies/yakdicotext_zone6.xml" output="/home/bitnami/stack/exalead/cloudview-V6R2013.SP2.44163-linux-x64/resource/all-arch/yakwala/dico/yakdicotext_zone6/" > /home/bitnami/stack/apache2/htdocs/LOG/PREPROD/ontoBuilder_zone6.log

