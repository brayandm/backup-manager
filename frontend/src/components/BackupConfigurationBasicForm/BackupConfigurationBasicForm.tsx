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
  storageServerNames: { id: string; name: string }[];
  storageServers: { id: string; name: string }[];
  setStorageServers: React.Dispatch<
    React.SetStateAction<{ id: string; name: string }[]>
  >;
  name: string;
  setName: React.Dispatch<React.SetStateAction<string>>;
  setMissingValues: React.Dispatch<React.SetStateAction<boolean>>;
}

function BackupConfigurationBasicForm({
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
    } else {
      setMissingValues(false);
    }
  }, [name, setMissingValues]);

  console.log("storageServers", storageServers);

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
        style={{ display: "flex", flexDirection: "column", marginTop: "25px" }}
      >
        {storageServers.map(
          (storageServer: { id: string; name: string }, index: number) => (
            <div
              key={storageServer.id}
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
                          value={storageServer.id + " - " + storageServer.name}
                          id="storage_server"
                          labelId="storage_server"
                          variant="outlined"
                          label="Storage Server *"
                          onChange={(event) => {
                            const objs = [...storageServers];
                            objs[index] = {
                              id: event.target.value.split(" - ")[0],
                              name: event.target.value.split(" - ")[1],
                            };
                            setStorageServers(objs);
                          }}
                          size="medium"
                          sx={{
                            width: "400px",
                          }}
                        >
                          {storageServerNames.map((storageServerName) => (
                            <MenuItem
                              key={storageServerName.id}
                              value={
                                storageServerName.id +
                                " - " +
                                storageServerName.name
                              }
                            >
                              {storageServerName.id +
                                " - " +
                                storageServerName.name}
                            </MenuItem>
                          ))}
                        </Select>
                      </FormControl>
                    </div>
                    <IconButton
                      onClick={() => {
                        const objs = storageServers;
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
                id: "",
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
    </>
  );
}

export default BackupConfigurationBasicForm;
