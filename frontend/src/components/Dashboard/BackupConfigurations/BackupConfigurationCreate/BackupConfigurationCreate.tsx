"use client";

import TabSection from "@/components/TabSection";
import ArrowBackIcon from "@mui/icons-material/ArrowBack";
import { IconButton, Tooltip } from "@mui/material";

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
    <>
      <Tooltip title="Back" placement="right-start">
        <IconButton aria-label="back" onClick={handleGoBack}>
          <ArrowBackIcon fontSize="inherit" />
        </IconButton>
      </Tooltip>
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
    </>
  );
}

export default BackupConfigurationCreate;
