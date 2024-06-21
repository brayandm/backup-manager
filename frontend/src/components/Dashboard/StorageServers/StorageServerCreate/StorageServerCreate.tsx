"use client";

import TabSection from "@/components/TabSection";
import ArrowBackIcon from "@mui/icons-material/ArrowBack";
import { Button, IconButton, TextField, Tooltip } from "@mui/material";
import AddIcon from "@mui/icons-material/Add";
import { useState } from "react";

interface StorageServerCreateProps {
  render: boolean;
  setRender: React.Dispatch<React.SetStateAction<boolean>>;
}

function StorageServerCreate({ render, setRender }: StorageServerCreateProps) {
  const handleGoBack = () => {
    const searchParams = new URLSearchParams(window.location.search);
    searchParams.delete("option");
    window.history.replaceState(
      {},
      "",
      `${window.location.pathname}?${searchParams}`
    );
    setRender(!render);
  };

  const [name, setName] = useState("");
  const [connection, setConnection] = useState("");
  const [driver, setDriver] = useState("");

  return (
    <div style={{ position: "relative" }}>
      <div
        style={{
          position: "absolute",
          display: "flex",
          justifyContent: "center",
          alignItems: "center",
          top: "0",
          left: "0",
          zIndex: 1,
          width: 80,
          height: 80,
        }}
      >
        <Tooltip title="Back" placement="right-start">
          <IconButton aria-label="back" onClick={handleGoBack}>
            <ArrowBackIcon fontSize="inherit" />
          </IconButton>
        </Tooltip>
      </div>
      <div
        style={{
          position: "absolute",
          display: "flex",
          justifyContent: "center",
          alignItems: "center",
          bottom: "40px",
          right: "40px",
          zIndex: 1,
        }}
      >
        <Button variant="contained" endIcon={<AddIcon />}>
          Create
        </Button>
      </div>
      <TabSection
        tabs={[
          {
            label: "Basic",
            component: (
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
                />
              </>
            ),
          },
          {
            label: "Connection",
            component: <div>Create 2</div>,
          },
          {
            label: "Driver",
            component: <div>Create 3</div>,
          },
        ]}
      />
    </div>
  );
}

export default StorageServerCreate;
