#!/bin/bash
docker compose exec php bin/console doctrine:fixtures:load
