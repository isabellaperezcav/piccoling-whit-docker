FROM node:20

WORKDIR /microFacturas

COPY src/controllers /microFacturas/src/controllers
COPY src/models /microFacturas/src/models
COPY src/index.js /microFacturas/src

RUN npm init --yes
RUN npm install express morgan mysql mysql2 axios

EXPOSE 3003

CMD node src/index.js