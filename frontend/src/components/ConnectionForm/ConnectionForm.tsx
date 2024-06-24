"use client";

import {
  Button,
  Card,
  CardContent,
  FormControl,
  IconButton,
  InputLabel,
  MenuItem,
  Select,
  TextField,
} from "@mui/material";
import React from "react";
import AddIcon from "@mui/icons-material/Add";
import DisabledByDefaultIcon from "@mui/icons-material/DisabledByDefault";
import KeyboardDoubleArrowDownIcon from "@mui/icons-material/KeyboardDoubleArrowDown";
import KeyboardDoubleArrowUpIcon from "@mui/icons-material/KeyboardDoubleArrowUp";

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
    <div style={{ display: "flex", flexDirection: "column" }}>
      {JSON.parse(connection).map((conn: any, index: number) => (
        <div
          key={index}
          style={{
            display: "flex",
            flexDirection: "column",
            justifyContent: "center",
            width: "70vw",
          }}
        >
          <div key={index}>
            <Card
              sx={{
                backgroundColor: "#fafafa",
              }}
            >
              <CardContent sx={{ position: "relative" }}>
                <div
                  style={{
                    display: "flex",
                    flexDirection: "row",
                    alignItems: "center",
                    gap: "10px",
                  }}
                >
                  <FormControl
                    sx={{
                      width: "180px",
                      marginBottom: "8px",
                      marginTop: "16px",
                    }}
                  >
                    <InputLabel id="connection_type">
                      Connection Type *
                    </InputLabel>
                    <Select
                      value={conn.type}
                      id="Connection Type"
                      labelId="connection_type"
                      variant="outlined"
                      label="Connection Type *"
                      onChange={(event) => {
                        const objs = JSON.parse(connection);
                        objs[index].type = event.target.value;
                        setConnection(JSON.stringify(objs));
                      }}
                      size="medium"
                    >
                      {connections.map((connection) => (
                        <MenuItem key={connection.type} value={connection.type}>
                          {connection.label}
                        </MenuItem>
                      ))}
                    </Select>
                  </FormControl>

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
                        sx={{ width: "150px" }}
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
                        sx={{ width: "130px" }}
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
                        sx={{ width: "80px" }}
                      />
                      <FormControl
                        sx={{
                          width: "220px",
                          marginBottom: "8px",
                          marginTop: "16px",
                        }}
                      >
                        <InputLabel id="private_key_type">
                          Private Key Type *
                        </InputLabel>
                        <Select
                          value={conn.private_key_type}
                          id="private_key_type"
                          labelId="private_key_type"
                          variant="outlined"
                          label="Private Key Type *"
                          required
                          onChange={(event) => {
                            const objs = JSON.parse(connection);
                            objs[index].private_key_type = event.target.value;
                            setConnection(JSON.stringify(objs));
                          }}
                          size="medium"
                        >
                          <MenuItem value="file">File Path</MenuItem>
                          <MenuItem value="text">Key Plain Text</MenuItem>
                        </Select>
                      </FormControl>
                      <TextField
                        id="private_key"
                        key="private_key"
                        label="Private Key"
                        variant="outlined"
                        margin="normal"
                        type="text"
                        required
                        value={conn.private_key}
                        onChange={(event) => {
                          const objs = JSON.parse(connection);
                          objs[index].private_key = event.target.value;
                          setConnection(JSON.stringify(objs));
                        }}
                        sx={{ width: "200px" }}
                      />
                      <TextField
                        id="passphrase"
                        key="passphrase"
                        label="Passphrase"
                        variant="outlined"
                        margin="normal"
                        type="text"
                        value={conn.passphrase}
                        onChange={(event) => {
                          const objs = JSON.parse(connection);
                          objs[index].passphrase = event.target.value;
                          setConnection(JSON.stringify(objs));
                        }}
                        sx={{ width: "200px" }}
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
                <IconButton
                  onClick={() => {
                    const objs = JSON.parse(connection);
                    objs.splice(index, 1);
                    setConnection(JSON.stringify(objs));
                  }}
                  sx={{
                    position: "absolute",
                    right: "0px",
                    top: "0px",
                  }}
                >
                  <DisabledByDefaultIcon />
                </IconButton>
              </CardContent>
            </Card>
          </div>
          {index !== JSON.parse(connection).length - 1 && (
            <div
              key={index}
              style={{
                display: "flex",
                justifyContent: "center",
                alignItems: "center",
              }}
            >
              <KeyboardDoubleArrowDownIcon
                key={index}
                sx={{
                  width: "50px",
                  height: "50px",
                  margin: "10px",
                  cursor: "pointer",
                }}
                onClick={() => {
                  const objs = JSON.parse(connection);
                  const temp = objs[index];
                  objs[index] = objs[index + 1];
                  objs[index + 1] = temp;
                  setConnection(JSON.stringify(objs));
                }}
              />
              <KeyboardDoubleArrowUpIcon
                key={index}
                sx={{
                  width: "50px",
                  height: "50px",
                  margin: "10px",
                  cursor: "pointer",
                }}
                onClick={() => {
                  const objs = JSON.parse(connection);
                  const temp = objs[index + 1];
                  objs[index + 1] = objs[index];
                  objs[index] = temp;
                  setConnection(JSON.stringify(objs));
                }}
              />
            </div>
          )}
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
        sx={{ width: "250px", marginTop: "25px" }}
      >
        Add Connection
      </Button>
    </div>
  );
}

export default ConnectionForm;
