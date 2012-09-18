#!/bin/bash
cp ./output/yakdicotitle.xml /home/bitnami/stack/exalead/cloudview-V6R2013.SP2.44163-linux-x64/resource/all-arch/yakwala/ontologies/yakdicotitle.xml
/home/bitnami/stack/exalead/theindex/bin/cvadmin linguistic compile-ontology input="/home/bitnami/stack/exalead/cloudview-V6R2013.SP2.44163-linux-x64/resource/all-arch/yakwala/ontologies/yakdicotitle.xml" output="/home/bitnami/stack/exalead/cloudview-V6R2013.SP2.44163-linux-x64/resource/all-arch/yakwala/dico/yakdicotitle/"
