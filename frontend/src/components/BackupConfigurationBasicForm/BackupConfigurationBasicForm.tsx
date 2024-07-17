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
import React, { useEffect } from "react";
import AddIcon from "@mui/icons-material/Add";
import DisabledByDefaultIcon from "@mui/icons-material/DisabledByDefault";

interface BackupConfigurationBasicFormProps {
  dataSourceNames: { id: number; name: string }[];
  dataSources: { id: number; name: string }[];
  setDataSources: React.Dispatch<
    React.SetStateAction<{ id: number; name: string }[]>
  >;
  storageServerNames: { id: number; name: string }[];
  storageServers: { id: number; name: string }[];
  setStorageServers: React.Dispatch<
    React.SetStateAction<{ id: number; name: string }[]>
  >;
  name: string;
  setName: React.Dispatch<React.SetStateAction<string>>;
  setMissingValues: React.Dispatch<React.SetStateAction<boolean>>;
}

function BackupConfigurationBasicForm({
  dataSourceNames,
  dataSources,
  setDataSources,
  storageServerNames,
  storageServers,
  setStorageServers,
  name,
  setName,
  setMissingValues,
}: BackupConfigurationBasicFormProps) {
  useEffect(() => {
    if (name === "") {
      setMissingValues(true);
    } else if (storageServers.length === 0) {
      setMissingValues(true);
    } else if (storageServers.some((server) => server.id === 0)) {
      setMissingValues(true);
    } else if (dataSources.length === 0) {
      setMissingValues(true);
    } else if (dataSources.some((source) => source.id === 0)) {
      setMissingValues(true);
    } else {
      setMissingValues(false);
    }
  }, [name, storageServers, dataSources, setMissingValues]);

  return (
    <>
      <TextField
        id="name"
        label="Name"
        variant="outlined"
        margin="normal"
        type="text"
        required
        value={name}
        onChange={(event) => setName(event.target.value)}
        sx={{
          margin: 0,
        }}
      />
      <div
        style={{
          display: "flex",
          flexDirection: "row",
          marginTop: "100px",
          gap: "100px",
        }}
      >
        <div
          style={{
            display: "flex",
            flexDirection: "column",
            marginTop: "25px",
            width: "30vw",
          }}
        >
          {dataSources.map(
            (dataSource: { id: number; name: string }, index: number) => (
              <div
                key={index}
                style={{
                  display: "flex",
                  flexDirection: "column",
                  justifyContent: "center",
                  width: "30vw",
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
                          <InputLabel id="data_source">
                            Data Source *
                          </InputLabel>
                          <Select
                            value={
                              String(dataSource.id) + " - " + dataSource.name
                            }
                            id="data_source"
                            labelId="data_source"
                            variant="outlined"
                            label="Data Source *"
                            onChange={(event) => {
                              const objs = [...dataSources];
                              objs[index] = {
                                id: Number(event.target.value.split(" - ")[0]),
                                name: event.target.value.split(" - ")[1],
                              };
                              setDataSources(objs);
                            }}
                            size="medium"
                            sx={{
                              width: "400px",
                            }}
                          >
                            {dataSourceNames.map(
                              (dataSourceName) =>
                                (!dataSources.some(
                                  (server) => server.id === dataSourceName.id
                                ) ||
                                  dataSource.id === dataSourceName.id) && (
                                  <MenuItem
                                    key={dataSourceName.id}
                                    value={
                                      String(dataSourceName.id) +
                                      " - " +
                                      dataSourceName.name
                                    }
                                  >
                                    {String(dataSourceName.id) +
                                      " - " +
                                      dataSourceName.name}
                                  </MenuItem>
                                )
                            )}
                          </Select>
                        </FormControl>
                      </div>
                      <IconButton
                        onClick={() => {
                          const objs = [...dataSources];
                          objs.splice(index, 1);
                          setDataSources(objs);
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
              </div>
            )
          )}
          <Button
            variant="contained"
            endIcon={<AddIcon />}
            onClick={() => {
              setDataSources([
                ...dataSources,
                {
                  id: 0,
                  name: "",
                },
              ]);
            }}
            size="large"
            sx={{
              width: "280px",
              marginTop: dataSources.length > 0 ? "25px" : "0px",
            }}
          >
            Add Data Source
          </Button>
        </div>
        <div
          style={{
            display: "flex",
            flexDirection: "column",
            marginTop: "25px",
            width: "30vw",
          }}
        >
          {storageServers.map(
            (storageServer: { id: number; name: string }, index: number) => (
              <div
                key={index}
                style={{
                  display: "flex",
                  flexDirection: "column",
                  justifyContent: "center",
                  width: "30vw",
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
                          <InputLabel id="storage_server">
                            Storage Server *
                          </InputLabel>
                          <Select
                            value={
                              String(storageServer.id) +
                              " - " +
                              storageServer.name
                            }
                            id="storage_server"
                            labelId="storage_server"
                            variant="outlined"
                            label="Storage Server *"
                            onChange={(event) => {
                              const objs = [...storageServers];
                              objs[index] = {
                                id: Number(event.target.value.split(" - ")[0]),
                                name: event.target.value.split(" - ")[1],
                              };
                              setStorageServers(objs);
                            }}
                            size="medium"
                            sx={{
                              width: "400px",
                            }}
                          >
                            {storageServerNames.map(
                              (storageServerName) =>
                                (!storageServers.some(
                                  (server) => server.id === storageServerName.id
                                ) ||
                                  storageServer.id ===
                                    storageServerName.id) && (
                                  <MenuItem
                                    key={storageServerName.id}
                                    value={
                                      String(storageServerName.id) +
                                      " - " +
                                      storageServerName.name
                                    }
                                  >
                                    {String(storageServerName.id) +
                                      " - " +
                                      storageServerName.name}
                                  </MenuItem>
                                )
                            )}
                          </Select>
                        </FormControl>
                      </div>
                      <IconButton
                        onClick={() => {
                          const objs = [...storageServers];
                          objs.splice(index, 1);
                          setStorageServers(objs);
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
              </div>
            )
          )}
          <Button
            variant="contained"
            endIcon={<AddIcon />}
            onClick={() => {
              setStorageServers([
                ...storageServers,
                {
                  id: 0,
                  name: "",
                },
              ]);
            }}
            size="large"
            sx={{
              width: "280px",
              marginTop: storageServers.length > 0 ? "25px" : "0px",
            }}
          >
            Add Storage Server
          </Button>
        </div>
      </div>
    </>
  );
}

export default BackupConfigurationBasicForm;
