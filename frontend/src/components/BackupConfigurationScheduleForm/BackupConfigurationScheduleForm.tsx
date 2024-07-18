"use client";

import { Checkbox, TextField, FormControlLabel } from "@mui/material";
import React, { useEffect } from "react";

interface BackupConfigurationScheduleFormProps {
  scheduleCron: string;
  setScheduleCron: React.Dispatch<React.SetStateAction<string>>;
  setMissingValues: React.Dispatch<React.SetStateAction<boolean>>;
}

function BackupConfigurationScheduleForm({
  scheduleCron,
  setScheduleCron,
  setMissingValues,
}: BackupConfigurationScheduleFormProps) {
  const [manualBackup, setManualBackup] = React.useState(false);

  useEffect(() => {
    if (!scheduleCron && !manualBackup) {
      setMissingValues(true);
    } else {
      setMissingValues(false);
    }
  }, [scheduleCron, manualBackup, setMissingValues]);

  return (
    <div
      style={{
        display: "flex",
        flexDirection: "column",
        gap: "16px",
      }}
    >
      <TextField
        id="schedule-cron"
        key="schedule-cron"
        label="Schedule Cron"
        variant="outlined"
        margin="normal"
        type="text"
        required
        value={scheduleCron}
        onChange={(event) => {
          setScheduleCron(event.target.value);
        }}
        sx={{
          margin: 0,
        }}
        disabled={manualBackup}
      />

      <FormControlLabel
        control={
          <Checkbox
            checked={manualBackup}
            onChange={(event) => {
              setManualBackup(event.target.checked);
              setScheduleCron("");
            }}
            color="primary"
          />
        }
        label="Backup manual"
      />
    </div>
  );
}

export default BackupConfigurationScheduleForm;
