"use client";

import { FormControl, InputLabel, MenuItem, Select } from "@mui/material";
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

  return (
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
  );
}

export default BackupConfigurationAdvancedForm;
