"use client";

import { FormControl, InputLabel, MenuItem, Select } from "@mui/material";
import React, { useEffect } from "react";

interface MigrationConfigurationAdvancedFormProps {
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

function MigrationConfigurationAdvancedForm({
  compression,
  setCompression,
  setMissingValues,
}: MigrationConfigurationAdvancedFormProps) {
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

  useEffect(() => {
    setMissingValues(false);
  }, [setMissingValues]);

  return (
    <div
      style={{
        display: "flex",
        flexDirection: "row",
        gap: "100px",
      }}
    >
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
      </div>
    </div>
  );
}

export default MigrationConfigurationAdvancedForm;
