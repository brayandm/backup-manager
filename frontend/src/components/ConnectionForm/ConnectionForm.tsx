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
      {JSON.parse(connection).map((conn: any, index: number) => (
        <div key={index}>
          {conn.type === "ssh" ? (
            <div
              style={{
                display: "flex",
                flexDirection: "row",
                gap: "10px",
                width: "400px",
              }}
            >
              {conn.user}
            </div>
          ) : null}
        </div>
      ))}
      <Button
        variant="contained"
        endIcon={<AddIcon />}
        onClick={() => {
          const objs = JSON.parse("[]");
          objs.push(JSON.parse("{}"));
          objs[0]["type"] = "ssh";
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
