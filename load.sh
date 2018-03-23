#!/usr/bin/env bash
bin/console doctrine:schema:drop --force
bin/console doctrine:schema:update --force
bin/console acecommerce:inituser
bin/console doctrine:fixtures:load -n --append
read -p "Press enter to continue" nothing