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

interface MigrationConfigurationBasicFormProps {
  dataSourceNames: { id: number; name: string }[];
  endDataSources: { id: number; name: string }[];
  setEndDataSources: React.Dispatch<
    React.SetStateAction<{ id: number; name: string }[]>
  >;
  originDataSource: { id: number; name: string } | undefined;
  setOriginDataSource: React.Dispatch<
    React.SetStateAction<{ id: number; name: string } | undefined>
  >;
  name: string;
  setName: React.Dispatch<React.SetStateAction<string>>;
  setMissingValues: React.Dispatch<React.SetStateAction<boolean>>;
}

function MigrationConfigurationBasicForm({
  dataSourceNames,
  endDataSources,
  setEndDataSources,
  originDataSource,
  setOriginDataSource,
  name,
  setName,
  setMissingValues,
}: MigrationConfigurationBasicFormProps) {
  useEffect(() => {
    if (name === "") {
      setMissingValues(true);
    } else if (endDataSources.length === 0) {
      setMissingValues(true);
    } else if (endDataSources.some((source) => source.id === 0)) {
      setMissingValues(true);
    } else {
      setMissingValues(false);
    }
  }, [name, endDataSources, setMissingValues]);

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
          {endDataSources.map(
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
                          <InputLabel id="data_target">
                            Data Target *
                          </InputLabel>
                          <Select
                            value={
                              String(dataSource.id) + " - " + dataSource.name
                            }
                            id="data_target"
                            labelId="data_target"
                            variant="outlined"
                            label="Data Target *"
                            onChange={(event) => {
                              const objs = [...endDataSources];
                              objs[index] = {
                                id: Number(event.target.value.split(" - ")[0]),
                                name: event.target.value.split(" - ")[1],
                              };
                              setEndDataSources(objs);
                            }}
                            size="medium"
                            sx={{
                              width: "400px",
                            }}
                          >
                            {dataSourceNames.map(
                              (dataSourceName) =>
                                (!endDataSources.some(
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
                          const objs = [...endDataSources];
                          objs.splice(index, 1);
                          setEndDataSources(objs);
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
              setEndDataSources([
                ...endDataSources,
                {
                  id: 0,
                  name: "",
                },
              ]);
            }}
            size="large"
            sx={{
              width: "280px",
              marginTop: endDataSources.length > 0 ? "25px" : "0px",
            }}
          >
            Add Data Target
          </Button>
        </div>
      </div>
    </>
  );
}

export default MigrationConfigurationBasicForm;
