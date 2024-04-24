FROM node:20

WORKDIR /microUsuarios

COPY src/controllers /microUsuarios/src/controllers
COPY src/models /microUsuarios/src/models
COPY src/index.js /microUsuarios/src

RUN npm init --yes
RUN npm install express morgan mysql mysql2 axios

EXPOSE 3001

CMD node src/index.js