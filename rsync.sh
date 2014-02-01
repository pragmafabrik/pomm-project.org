#!/bin/bash

if [ "$1" != "go" ];
then
  DRY_RUN="--dry-run"
else
  DRY_RUN=""
fi

rsync -avz -e ssh $DRY_RUN --copy-dirlinks --delete --exclude '.git' --exclude '*.swp' --exclude 'environment.php' --exclude "config.php" --exclude '*.sh' --exclude 'log' ./   stinger:/var/www/prod/pomm-project.org/
