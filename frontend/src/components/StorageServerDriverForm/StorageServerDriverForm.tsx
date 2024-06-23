"use client";

import { MenuItem, Select, TextField } from "@mui/material";
import React from "react";

interface StorageServerDriverFormProps {
  driver: string;
  setDriver: React.Dispatch<React.SetStateAction<string>>;
}

function StorageServerDriverForm({
  driver,
  setDriver,
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
      <Select
        value={JSON.parse(driver).type}
        onChange={(event) => {
          const obj = JSON.parse(driver);
          obj.type = event.target.value;
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
      {JSON.parse(driver).type === "files_system" ? (
        <div
          style={{
            display: "flex",
            flexDirection: "column",
            gap: "10px",
            width: "400px",
          }}
        >
          <TextField
            id="path"
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
          style={{
            display: "flex",
            flexDirection: "column",
            gap: "10px",
            width: "400px",
          }}
        >
          <TextField
            id="access_key_id"
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
