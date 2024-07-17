"use client";

import {
  FormControl,
  InputLabel,
  MenuItem,
  Select,
  TextField,
} from "@mui/material";
import React, { useEffect } from "react";

interface BackupConfigurationAdvancedFormProps {
  retentionPolicy: string;
  setRetentionPolicy: React.Dispatch<React.SetStateAction<string>>;
  compression: string;
  setCompression: React.Dispatch<React.SetStateAction<string>>;
  encryption: string;
  setEncryption: React.Dispatch<React.SetStateAction<string>>;
  integrityCheck: string;
  setIntegrityCheck: React.Dispatch<React.SetStateAction<string>>;
  setMissingValues: React.Dispatch<React.SetStateAction<boolean>>;
}

function BackupConfigurationAdvancedForm({
  retentionPolicy,
  setRetentionPolicy,
  compression,
  setCompression,
  encryption,
  setEncryption,
  integrityCheck,
  setIntegrityCheck,
  setMissingValues,
}: BackupConfigurationAdvancedFormProps) {
  const compressionOptions = [
    {
      type: "none",
      label: "None",
    },
    {
      type: "tar",
      label: "Tar",
    },
  ];

  const encryptionOptions = [
    {
      type: "none",
      label: "None",
    },
    {
      type: "aes-256-cbc",
      label: "AES-256-CBC",
    },
  ];

  const integrityCheckOptions = [
    {
      type: "none",
      label: "None",
    },
    {
      type: "sha-256-sum",
      label: "SHA-256-Sum",
    },
  ];

  useEffect(() => {
    if (JSON.parse(retentionPolicy).keep_all_backups_for_days === "") {
      setMissingValues(true);
    } else {
    }
  }, [retentionPolicy, setMissingValues]);

  return (
    <div
      style={{
        display: "flex",
        flexDirection: "row",
      }}
    >
      <div
        style={{
          display: "flex",
          flexDirection: "column",
          gap: "16px",
        }}
      >
        <TextField
          id="keep-all-backups-for-days"
          label="Keep all backups for days"
          variant="outlined"
          margin="normal"
          type="number"
          required
          value={JSON.parse(retentionPolicy).keep_all_backups_for_days}
          onChange={(event) => {
            const obj = JSON.parse(retentionPolicy);
            obj.keep_all_backups_for_days = event.target.value;
            setRetentionPolicy(JSON.stringify(obj));
          }}
          sx={{
            margin: 0,
          }}
        />
        <TextField
          id="keep-daily-backups-for-days"
          label="Keep daily backups for days"
          variant="outlined"
          margin="normal"
          type="number"
          required
          value={JSON.parse(retentionPolicy).keep_daily_backups_for_days}
          onChange={(event) => {
            const obj = JSON.parse(retentionPolicy);
            obj.keep_daily_backups_for_days = event.target.value;
            setRetentionPolicy(JSON.stringify(obj));
          }}
          sx={{
            margin: 0,
          }}
        />
        <TextField
          id="keep-weekly-backups-for-weeks"
          label="Keep weekly backups for weeks"
          variant="outlined"
          margin="normal"
          type="number"
          required
          value={JSON.parse(retentionPolicy).keep_weekly_backups_for_weeks}
          onChange={(event) => {
            const obj = JSON.parse(retentionPolicy);
            obj.keep_weekly_backups_for_weeks = event.target.value;
            setRetentionPolicy(JSON.stringify(obj));
          }}
          sx={{
            margin: 0,
          }}
        />
        <TextField
          id="keep-monthly-backups-for-months"
          label="Keep monthly backups for months"
          variant="outlined"
          margin="normal"
          type="number"
          required
          value={JSON.parse(retentionPolicy).keep_monthly_backups_for_months}
          onChange={(event) => {
            const obj = JSON.parse(retentionPolicy);
            obj.keep_monthly_backups_for_months = event.target.value;
            setRetentionPolicy(JSON.stringify(obj));
          }}
          sx={{
            margin: 0,
          }}
        />
        <TextField
          id="keep-yearly-backups-for-years"
          label="Keep yearly backups for years"
          variant="outlined"
          margin="normal"
          type="number"
          required
          value={JSON.parse(retentionPolicy).keep_yearly_backups_for_years}
          onChange={(event) => {
            const obj = JSON.parse(retentionPolicy);
            obj.keep_yearly_backups_for_years = event.target.value;
            setRetentionPolicy(JSON.stringify(obj));
          }}
          sx={{
            margin: 0,
          }}
        />
        <TextField
          id="delete-oldest-backups-when-using-more-megabytes-than"
          label="Delete oldest backups when using more megabytes than"
          variant="outlined"
          margin="normal"
          type="number"
          required
          value={
            JSON.parse(retentionPolicy)
              .delete_oldest_backups_when_using_more_megabytes_than
          }
          onChange={(event) => {
            const obj = JSON.parse(retentionPolicy);
            obj.delete_oldest_backups_when_using_more_megabytes_than =
              event.target.value;
            setRetentionPolicy(JSON.stringify(obj));
          }}
          sx={{
            margin: 0,
          }}
        />
      </div>
      <div
        style={{
          display: "flex",
          flexDirection: "column",
          gap: "16px",
        }}
      >
        <FormControl
          sx={{
            width: "200px",
            marginBottom: "8px",
            marginTop: "16px",
          }}
        >
          <InputLabel id="compression">Compression</InputLabel>
          <Select
            value={JSON.parse(compression).type}
            id="compression"
            labelId="compression"
            variant="outlined"
            label="Compression"
            onChange={(event) => {
              setCompression(JSON.stringify({ type: event.target.value }));
            }}
            size="medium"
          >
            {compressionOptions.map((option) => (
              <MenuItem key={option.type} value={option.type}>
                {option.label}
              </MenuItem>
            ))}
          </Select>
        </FormControl>
        <FormControl
          sx={{
            width: "200px",
            marginBottom: "8px",
            marginTop: "16px",
          }}
        >
          <InputLabel id="encryption">Encryption</InputLabel>
          <Select
            value={JSON.parse(encryption).type}
            id="encryption"
            labelId="encryption"
            variant="outlined"
            label="Encryption"
            onChange={(event) => {
              setEncryption(
                JSON.stringify({ type: event.target.value, key: null })
              );
            }}
            size="medium"
          >
            {encryptionOptions.map((option) => (
              <MenuItem key={option.type} value={option.type}>
                {option.label}
              </MenuItem>
            ))}
          </Select>
        </FormControl>
        <FormControl
          sx={{
            width: "200px",
            marginBottom: "8px",
            marginTop: "16px",
          }}
        >
          <InputLabel id="integrity-check">Integrity Check</InputLabel>
          <Select
            value={JSON.parse(integrityCheck).type}
            id="integrity-check"
            labelId="integrity-check"
            variant="outlined"
            label="Integrity Check"
            onChange={(event) => {
              setIntegrityCheck(
                JSON.stringify({ type: event.target.value, hash: null })
              );
            }}
            size="medium"
          >
            {integrityCheckOptions.map((option) => (
              <MenuItem key={option.type} value={option.type}>
                {option.label}
              </MenuItem>
            ))}
          </Select>
        </FormControl>
      </div>
    </div>
  );
}

export default BackupConfigurationAdvancedForm;
