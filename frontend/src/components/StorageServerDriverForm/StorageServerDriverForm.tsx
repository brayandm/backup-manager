"use client";

import {
  FormControl,
  InputLabel,
  MenuItem,
  Select,
  TextField,
} from "@mui/material";
import React from "react";

interface StorageServerDriverFormProps {
  driver: string;
  setDriver: React.Dispatch<React.SetStateAction<string>>;
  setDriverTabMissingValues: React.Dispatch<React.SetStateAction<boolean>>;
}

function StorageServerDriverForm({
  driver,
  setDriver,
  setDriverTabMissingValues,
}: StorageServerDriverFormProps) {
  const drivers = [
    {
      type: "files_system",
      label: "Files System",
    },
    {
      type: "aws_s3",
      label: "AWS S3",
    },
  ];

  return (
    <>
      <FormControl>
        <InputLabel id="driver_type">Driver Type *</InputLabel>
        <Select
          labelId="driver_type"
          id="driver_type"
          label="Driver Type *"
          value={JSON.parse(driver).type}
          onChange={(event) => {
            const obj = JSON.parse("{}");
            obj["type"] = event.target.value;
            setDriver(JSON.stringify(obj));
          }}
          sx={{ width: "200px" }}
        >
          {drivers.map((driver) => (
            <MenuItem key={driver.type} value={driver.type}>
              {driver.label}
            </MenuItem>
          ))}
        </Select>
      </FormControl>
      {JSON.parse(driver).type === "files_system" ? (
        <div
          id="files_system"
          style={{
            display: "flex",
            flexDirection: "column",
            gap: "10px",
            width: "400px",
          }}
        >
          <TextField
            id="path"
            key="path"
            label="Path"
            variant="outlined"
            margin="normal"
            type="text"
            required
            value={JSON.parse(driver).path}
            onChange={(event) => {
              const obj = JSON.parse(driver);
              obj.path = event.target.value;
              setDriver(JSON.stringify(obj));
            }}
          />
        </div>
      ) : JSON.parse(driver).type === "aws_s3" ? (
        <div
          id="aws_s3"
          style={{
            display: "flex",
            flexDirection: "column",
            gap: "10px",
            width: "400px",
          }}
        >
          <TextField
            id="access_key_id"
            key="access_key"
            label="Access Key ID"
            variant="outlined"
            margin="normal"
            type="text"
            required
            value={JSON.parse(driver).key}
            onChange={(event) => {
              const obj = JSON.parse(driver);
              obj.key = event.target.value;
              setDriver(JSON.stringify(obj));
            }}
          />
          <TextField
            id="secret_access_key"
            key="secret_key"
            label="Secret Access Key"
            variant="outlined"
            margin="normal"
            type="text"
            required
            value={JSON.parse(driver).secret}
            onChange={(event) => {
              const obj = JSON.parse(driver);
              obj.secret = event.target.value;
              setDriver(JSON.stringify(obj));
            }}
          />
          <TextField
            id="region"
            key="region"
            label="Region"
            variant="outlined"
            margin="normal"
            type="text"
            required
            value={JSON.parse(driver).region}
            onChange={(event) => {
              const obj = JSON.parse(driver);
              obj.region = event.target.value;
              setDriver(JSON.stringify(obj));
            }}
          />
          <TextField
            id="bucket"
            key="bucket"
            label="Bucket"
            variant="outlined"
            margin="normal"
            type="text"
            required
            value={JSON.parse(driver).bucket}
            onChange={(event) => {
              const obj = JSON.parse(driver);
              obj.bucket = event.target.value;
              setDriver(JSON.stringify(obj));
            }}
          />
        </div>
      ) : null}
    </>
  );
}

export default StorageServerDriverForm;
