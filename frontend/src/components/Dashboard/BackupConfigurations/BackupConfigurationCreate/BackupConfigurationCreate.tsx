"use client";

import TabSection from "@/components/TabSection";
import ArrowBackIcon from "@mui/icons-material/ArrowBack";
import { Button, IconButton, Tooltip } from "@mui/material";
import SendIcon from "@mui/icons-material/Send";

interface BackupConfigurationCreateProps {
  render: boolean;
  setRender: React.Dispatch<React.SetStateAction<boolean>>;
}

function BackupConfigurationCreate({
  render,
  setRender,
}: BackupConfigurationCreateProps) {
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
        <Button variant="contained" endIcon={<SendIcon />}>
          Create
        </Button>
      </div>
      <TabSection
        tabs={[
          {
            label: "Create 1",
            component: <div>Create 1</div>,
          },
          {
            label: "Create 2",
            component: <div>Create 2</div>,
          },
        ]}
      />
    </div>
  );
}

export default BackupConfigurationCreate;
