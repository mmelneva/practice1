FROM mysql:5.7.16

WORKDIR /var/www

ENV USER=test-task
ENV UID=1000
ENV GID=1000

RUN groupadd -g "$GID" "$USER"
RUN useradd -u "$UID" -ms /bin/bash -g "$USER" "$USER"

USER "$USER"