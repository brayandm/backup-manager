"use client";

import {
  FormControl,
  InputLabel,
  MenuItem,
  Select,
  TextField,
} from "@mui/material";
import React, { useEffect } from "react";

interface DataSourceDriverFormProps {
  driver: string;
  setDriver: React.Dispatch<React.SetStateAction<string>>;
  setMissingValues: React.Dispatch<React.SetStateAction<boolean>>;
}

function DataSourceDriverForm({
  driver,
  setDriver,
  setMissingValues,
}: DataSourceDriverFormProps) {
  const drivers = [
    {
      type: "files_system",
      label: "Files System",
    },
    {
      type: "mysql",
      label: "MySQL",
    },
    {
      type: "pgsql",
      label: "PostgreSQL",
    },
    {
      type: "aws_s3",
      label: "AWS S3",
    },
  ];

  useEffect(() => {
    if (JSON.parse(driver).type === "files_system") {
      if (!JSON.parse(driver).path) {
        setMissingValues(true);
      } else {
        setMissingValues(false);
      }
    } else if (JSON.parse(driver).type === "mysql") {
      if (
        !JSON.parse(driver).host ||
        !JSON.parse(driver).port ||
        !JSON.parse(driver).user ||
        !JSON.parse(driver).password ||
        !JSON.parse(driver).database
      ) {
        setMissingValues(true);
      } else {
        setMissingValues(false);
      }
    } else if (JSON.parse(driver).type === "pgsql") {
      if (
        !JSON.parse(driver).host ||
        !JSON.parse(driver).port ||
        !JSON.parse(driver).user ||
        !JSON.parse(driver).password ||
        !JSON.parse(driver).database
      ) {
        setMissingValues(true);
      } else {
        setMissingValues(false);
      }
    } else if (JSON.parse(driver).type === "aws_s3") {
      if (
        !JSON.parse(driver).key ||
        !JSON.parse(driver).secret ||
        !JSON.parse(driver).region ||
        !JSON.parse(driver).bucket
      ) {
        setMissingValues(true);
      } else {
        setMissingValues(false);
      }
    } else {
      setMissingValues(true);
    }
  }, [driver, setMissingValues]);

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

            if (obj.type === "mysql") {
              obj["port"] = "3306";
            } else if (obj.type === "pgsql") {
              obj["port"] = "5432";
            }

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
      ) : JSON.parse(driver).type === "mysql" ? (
        <div
          id="mysql"
          style={{
            display: "flex",
            flexDirection: "column",
            gap: "10px",
            width: "400px",
          }}
        >
          <TextField
            id="host"
            key="host"
            label="Host"
            variant="outlined"
            margin="normal"
            type="text"
            required
            value={JSON.parse(driver).host}
            onChange={(event) => {
              const obj = JSON.parse(driver);
              obj.host = event.target.value;
              setDriver(JSON.stringify(obj));
            }}
          />
          <TextField
            id="port"
            key="port"
            label="Port"
            variant="outlined"
            margin="normal"
            type="text"
            required
            value={JSON.parse(driver).port}
            onChange={(event) => {
              const obj = JSON.parse(driver);
              obj.port = event.target.value;
              setDriver(JSON.stringify(obj));
            }}
          />
          <TextField
            id="user"
            key="user"
            label="User"
            variant="outlined"
            margin="normal"
            type="text"
            required
            value={JSON.parse(driver).user}
            onChange={(event) => {
              const obj = JSON.parse(driver);
              obj.user = event.target.value;
              setDriver(JSON.stringify(obj));
            }}
          />
          <TextField
            id="password"
            key="password"
            label="Password"
            variant="outlined"
            margin="normal"
            type="password"
            required
            value={JSON.parse(driver).password}
            onChange={(event) => {
              const obj = JSON.parse(driver);
              obj.password = event.target.value;
              setDriver(JSON.stringify(obj));
            }}
          />
          <TextField
            id="database"
            key="database"
            label="Database"
            variant="outlined"
            margin="normal"
            type="text"
            required
            value={JSON.parse(driver).database}
            onChange={(event) => {
              const obj = JSON.parse(driver);
              obj.database = event.target.value;
              setDriver(JSON.stringify(obj));
            }}
          />
        </div>
      ) : JSON.parse(driver).type === "pgsql" ? (
        <div
          id="pgsql"
          style={{
            display: "flex",
            flexDirection: "column",
            gap: "10px",
            width: "400px",
          }}
        >
          <TextField
            id="host"
            key="host"
            label="Host"
            variant="outlined"
            margin="normal"
            type="text"
            required
            value={JSON.parse(driver).host}
            onChange={(event) => {
              const obj = JSON.parse(driver);
              obj.host = event.target.value;
              setDriver(JSON.stringify(obj));
            }}
          />
          <TextField
            id="port"
            key="port"
            label="Port"
            variant="outlined"
            margin="normal"
            type="text"
            required
            value={JSON.parse(driver).port}
            onChange={(event) => {
              const obj = JSON.parse(driver);
              obj.port = event.target.value;
              setDriver(JSON.stringify(obj));
            }}
          />
          <TextField
            id="user"
            key="user"
            label="User"
            variant="outlined"
            margin="normal"
            type="text"
            required
            value={JSON.parse(driver).user}
            onChange={(event) => {
              const obj = JSON.parse(driver);
              obj.user = event.target.value;
              setDriver(JSON.stringify(obj));
            }}
          />
          <TextField
            id="password"
            key="password"
            label="Password"
            variant="outlined"
            margin="normal"
            type="password"
            required
            value={JSON.parse(driver).password}
            onChange={(event) => {
              const obj = JSON.parse(driver);
              obj.password = event.target.value;
              setDriver(JSON.stringify(obj));
            }}
          />
          <TextField
            id="database"
            key="database"
            label="Database"
            variant="outlined"
            margin="normal"
            type="text"
            required
            value={JSON.parse(driver).database}
            onChange={(event) => {
              const obj = JSON.parse(driver);
              obj.database = event.target.value;
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
            type="password"
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
          <TextField
            id="endpoint"
            key="endpoint"
            label="Endpoint"
            variant="outlined"
            margin="normal"
            type="text"
            value={JSON.parse(driver).endpoint}
            onChange={(event) => {
              const obj = JSON.parse(driver);
              obj.endpoint = event.target.value;
              setDriver(JSON.stringify(obj));
            }}
          />
          <TextField
            id="path"
            key="path"
            label="Path"
            variant="outlined"
            margin="normal"
            type="text"
            value={JSON.parse(driver).path}
            onChange={(event) => {
              const obj = JSON.parse(driver);
              obj.path = event.target.value;
              setDriver(JSON.stringify(obj));
            }}
          />
        </div>
      ) : null}
    </>
  );
}

export default DataSourceDriverForm;
