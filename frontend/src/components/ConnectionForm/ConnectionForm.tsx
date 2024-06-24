"use client";

import {
  Button,
  Fab,
  IconButton,
  MenuItem,
  Select,
  TextField,
  Tooltip,
} from "@mui/material";
import React from "react";
import AddIcon from "@mui/icons-material/Add";

interface ConnectionFormProps {
  connection: string;
  setConnection: React.Dispatch<React.SetStateAction<string>>;
}

function ConnectionForm({ connection, setConnection }: ConnectionFormProps) {
  const connections = [
    {
      type: "ssh",
      label: "SSH",
    },
    {
      type: "docker",
      label: "Docker",
    },
  ];

  return (
    <>
      {JSON.parse(connection).map((conn: any, index: number) => (
        <div key={index}>
          <div
            style={{
              display: "flex",
              flexDirection: "row",
              gap: "10px",
              alignItems: "center",
            }}
          >
            <Select
              value={conn.type}
              onChange={(event) => {
                const objs = JSON.parse(connection);
                objs[index].type = event.target.value;
                setConnection(JSON.stringify(objs));
              }}
              // sx={{ width: "200px" }}
              size="medium"
            >
              {connections.map((connection) => (
                <MenuItem key={connection.type} value={connection.type}>
                  {connection.label}
                </MenuItem>
              ))}
            </Select>

            {conn.type === "ssh" ? (
              <>
                <TextField
                  id="user"
                  key="user"
                  label="User"
                  variant="outlined"
                  margin="normal"
                  type="text"
                  required
                  value={conn.user}
                  onChange={(event) => {
                    const objs = JSON.parse(connection);
                    objs[index].user = event.target.value;
                    setConnection(JSON.stringify(objs));
                  }}
                />
                <TextField
                  id="host"
                  key="host"
                  label="Host"
                  variant="outlined"
                  margin="normal"
                  type="text"
                  required
                  value={conn.host}
                  onChange={(event) => {
                    const objs = JSON.parse(connection);
                    objs[index].host = event.target.value;
                    setConnection(JSON.stringify(objs));
                  }}
                />
                <TextField
                  id="port"
                  key="port"
                  label="Port"
                  variant="outlined"
                  margin="normal"
                  type="text"
                  required
                  value={conn.port}
                  onChange={(event) => {
                    const objs = JSON.parse(connection);
                    objs[index].port = event.target.value;
                    setConnection(JSON.stringify(objs));
                  }}
                />
              </>
            ) : conn.type === "docker" ? (
              <>
                <TextField
                  id="container_name"
                  key="container_name"
                  label="Container Name"
                  variant="outlined"
                  margin="normal"
                  type="text"
                  required
                  value={conn.container_name}
                  onChange={(event) => {
                    const objs = JSON.parse(connection);
                    objs[index].container_name = event.target.value;
                    setConnection(JSON.stringify(objs));
                  }}
                />
              </>
            ) : null}
          </div>
        </div>
      ))}
      <Button
        variant="contained"
        endIcon={<AddIcon />}
        onClick={() => {
          const objs = JSON.parse(connection);
          const obj = JSON.parse("{}");
          objs.push(obj);
          setConnection(JSON.stringify(objs));
        }}
        size="large"
      >
        Add Connection
      </Button>
    </>
  );
}

export default ConnectionForm;
