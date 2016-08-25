DEPLOYMENT
==========

Prerequisites
-------------

* ansible with ansible-galaxy
* ansistrano-deploy (install: ```ansible-galaxy install carlosbuenosvinos.ansistrano-deploy```)

Deployment
----------

From repository's root:
```
ansible-playbook ansible/deploy.yml -i ansible/hosts_production -e 'ansistrano_deploy_to=/var/www/web-app'
```

