#!/usr/bin/env bash
cd $HOME

# only for auto deployments or as soon as labs / heroku env becomes available in app.json
if [ ! -z "$HEROKU_APP_NAME" ]; then

	# wordpress: values
	wp_url="$(echo $HEROKU_APP_NAME).herokuapp.com"
	wp_title="WordPress Heroku"
	wp_user="admin"
	wp_password="admin"
	wp_email="wordpress@wordpress.com"

	# wordpress: install
	vendor/bin/wp core install --url="$wp_url" --title="$wp_title" --admin_user="$wp_user" --admin_password="$wp_password" --admin_email="$wp_email"
fi
