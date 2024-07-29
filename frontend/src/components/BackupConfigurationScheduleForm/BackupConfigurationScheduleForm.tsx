"use client";

import {
  Checkbox,
  TextField,
  FormControlLabel,
  FormControl,
  InputLabel,
  Select,
  MenuItem,
} from "@mui/material";
import React, { useEffect } from "react";

interface BackupConfigurationScheduleFormProps {
  scheduleCron: string;
  setScheduleCron: React.Dispatch<React.SetStateAction<string>>;
  manualBackup: boolean;
  setManualBackup: React.Dispatch<React.SetStateAction<boolean>>;
  setMissingValues: React.Dispatch<React.SetStateAction<boolean>>;
}

function BackupConfigurationScheduleForm({
  scheduleCron,
  setScheduleCron,
  manualBackup,
  setManualBackup,
  setMissingValues,
}: BackupConfigurationScheduleFormProps) {
  const [minute, setMinute] = React.useState<string>("0");
  const [minuteType, setMinuteType] = React.useState<string>("at");
  const [hour, setHour] = React.useState<string>("0");
  const [hourType, setHourType] = React.useState<string>("at");
  const [dayOfMonth, setDayOfMonth] = React.useState<string>("1");
  const [dayOfMonthType, setDayOfMonthType] = React.useState<string>("every");
  const [month, setMonth] = React.useState<string>("1");
  const [monthType, setMonthType] = React.useState<string>("every");
  const [dayOfWeek, setDayOfWeek] = React.useState<string>("1");
  const [dayOfWeekType, setDayOfWeekType] = React.useState<string>("every");

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
      <div
        style={{
          display: "flex",
          flexDirection: "row",
          gap: "16px",
        }}
      >
        <FormControl
          sx={{
            width: "100px",
          }}
        >
          <InputLabel id="minute-type">Type</InputLabel>
          <Select
            value={minuteType}
            id="minute-type"
            labelId="minute-type"
            variant="outlined"
            label="Type"
            disabled={manualBackup}
            onChange={(event) => {
              setMinuteType(event.target.value);
            }}
            size="medium"
          >
            <MenuItem key="at" value="at">
              At
            </MenuItem>
            <MenuItem key="every" value="every">
              Every
            </MenuItem>
          </Select>
        </FormControl>
        <FormControl
          sx={{
            width: "200px",
          }}
        >
          <InputLabel id="minute">Minute</InputLabel>
          <Select
            value={minute}
            id="minute"
            labelId="minute"
            variant="outlined"
            label="Minute"
            disabled={manualBackup}
            onChange={(event) => {
              setMinute(event.target.value);
            }}
            size="medium"
          >
            {Array.from({ length: 60 }, (_, i) => i).map((minute) => (
              <MenuItem key={minute} value={minute}>
                {minute}
              </MenuItem>
            ))}
          </Select>
        </FormControl>
      </div>

      <FormControlLabel
        control={
          <Checkbox
            checked={manualBackup}
            onChange={(event) => {
              setManualBackup(event.target.checked);
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
