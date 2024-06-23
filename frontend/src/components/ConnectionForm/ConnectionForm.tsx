"use client";

import { Button, Fab, IconButton, Tooltip } from "@mui/material";
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
      <Button
        variant="contained"
        endIcon={<AddIcon />}
        onClick={() => {}}
        size="large"
      >
        Add Connection
      </Button>
    </>
  );
}

export default ConnectionForm;
