FROM node:20

WORKDIR /microInventario

COPY src/controllers /microInventario/src/controllers
COPY src/models /microInventario/src/models
COPY src/index.js /microInventario/src

RUN npm init --yes
RUN npm install express morgan mysql mysql2 axios

EXPOSE 3002

CMD node src/index.js