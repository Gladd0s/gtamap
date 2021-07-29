#!/bin/bash

script_dir=$(cd -P -- "$(dirname -- "${BASH_SOURCE[0]}")" && pwd -P)
backup_dir="$HOME/backup"

mkdir -pv "$backup_dir"
cp -v "$script_dir/gtamap.db" "$backup_dir/gtamap_$(date +"%Y_%m_%d_%I_%M_%p").db"
