"use client";

import { TextField } from "@mui/material";
import React, { useEffect } from "react";

interface StorageServerBasicFormProps {
  name: string;
  setName: React.Dispatch<React.SetStateAction<string>>;
  setMissingValues: React.Dispatch<React.SetStateAction<boolean>>;
}

function StorageServerBasicForm({
  name,
  setName,
  setMissingValues,
}: StorageServerBasicFormProps) {
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
    />
  );
}

export default StorageServerBasicForm;