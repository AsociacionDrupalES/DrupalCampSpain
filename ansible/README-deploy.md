DEPLOYMENT
==========

Prerequisites
-------------

* ansible with ansible-galaxy
* ansistrano-deploy locally installed (install: ```ansible-galaxy install -r ansible/requirements-deploy.yml```)

Deployment
----------

From repository's root:
```
ansible-playbook ansible/deploy.yml -i ansible/hosts_production -e 'ansistrano_deploy_to=/var/www/web-app'
```

