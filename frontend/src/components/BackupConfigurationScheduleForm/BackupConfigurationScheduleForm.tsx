"use client";

import { TextField } from "@mui/material";
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
  useEffect(() => {
    if (!scheduleCron) {
      setMissingValues(true);
    } else {
      setMissingValues(false);
    }
  }, [scheduleCron, setMissingValues]);

  return (
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
    />
  );
}

export default BackupConfigurationScheduleForm;
