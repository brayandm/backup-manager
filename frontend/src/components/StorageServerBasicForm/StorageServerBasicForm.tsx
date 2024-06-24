"use client";

import { TextField } from "@mui/material";
import React from "react";

interface StorageServerBasicFormProps {
  name: string;
  setName: React.Dispatch<React.SetStateAction<string>>;
}

function StorageServerBasicForm({
  name,
  setName,
}: StorageServerBasicFormProps) {
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
