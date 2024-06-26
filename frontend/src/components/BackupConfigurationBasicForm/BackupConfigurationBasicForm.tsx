"use client";

import { TextField } from "@mui/material";
import React, { useEffect } from "react";

interface BackupConfigurationBasicFormProps {
  storageServerNames: { id: string; name: string }[];
  storageServers: number[];
  setStorageServers: React.Dispatch<React.SetStateAction<number[]>>;
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

  return (
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
  );
}

export default BackupConfigurationBasicForm;
