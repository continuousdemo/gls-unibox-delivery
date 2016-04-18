SHELL := /bin/bash

GREEN   := "\\033[1;32m"
NORMAL  := "\\033[0;39m"
RED     := "\\033[1;31m"
PINK    := "\\033[1;35m"
BLUE    := "\\033[1;34m"
WHITE   := "\\033[0;02m"
YELLOW  := "\\033[1;33m"
CYAN    := "\\033[1;36m"

CID = $(shell docker ps | grep 'glsunixboxdelivery_php_1' | awk '{print $$1}')
USER = $(shell whoami)
GROUP = $(shell groups $(whoami) | cut -d' ' -f1)

docker-id:
	@echo -e $(BLUE)"Container ID of PHP : $(CID)"$(NORMAL);

connect:
	@docker exec -it $(CID) bash;

version:
	@cat .version

version-inc-major:
	@docker exec glsunixboxdelivery_php_1 /bin/sh -c 'cd /app && ./vendor/bin/phing inc-major'

version-inc-minor:
	@docker exec glsunixboxdelivery_php_1 /bin/sh -c 'cd /app && ./vendor/bin/phing inc-minor'

version-inc-patch:
	@docker exec glsunixboxdelivery_php_1 /bin/sh -c 'cd /app && ./vendor/bin/phing inc-patch'

tu-debug:
	php /app/vendor/bin/atoum -d /app/tests/units

tu:
	@docker exec glsunixboxdelivery_php_1 /bin/sh -c 'cd /app/ && php /app/vendor/bin/atoum -d /app/tests/units --use-light-report'

