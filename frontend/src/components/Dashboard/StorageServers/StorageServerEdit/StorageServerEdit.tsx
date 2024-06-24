"use client";

import TabSection from "@/components/TabSection";
import ArrowBackIcon from "@mui/icons-material/ArrowBack";
import { Alert, Button, IconButton, Tooltip } from "@mui/material";
import { useEffect, useState } from "react";
import ConnectionForm from "@/components/ConnectionForm";
import StorageServerDriverForm from "@/components/StorageServerDriverForm";
import StorageServerBasicForm from "@/components/StorageServerBasicForm";
import { get, put } from "@/lib/backendApi";
import EditNoteIcon from "@mui/icons-material/EditNote";

interface StorageServerEditProps {
  id: string;
  render: boolean;
  setRender: React.Dispatch<React.SetStateAction<boolean>>;
}

function StorageServerEdit({ id, render, setRender }: StorageServerEditProps) {
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

  const [name, setName] = useState("");
  const [connection, setConnection] = useState("[]");
  const [driver, setDriver] = useState("{}");

  useEffect(() => {
    const fetchStorageServer = async () => {
      const res = await get("/storage-servers/show/" + id);
      if (res.status === 200) {
        const data = (await res) as {
          data: {
            name: string;
            connection_config: string;
            driver_config: string;
          };
        };

        setName(data.data.name);
        setConnection(data.data.connection_config);
        setDriver(data.data.driver_config);
      }
    };
    fetchStorageServer();
  }, [id]);

  const [basicTabMissingValues, setBasicTabMissingValues] = useState(false);
  const [connectionTabMissingValues, setConnectionTabMissingValues] =
    useState(false);
  const [driverTabMissingValues, setDriverTabMissingValues] = useState(false);

  const missingValues =
    basicTabMissingValues ||
    connectionTabMissingValues ||
    driverTabMissingValues;
  const [onError, setOnError] = useState(false);
  const [onSuccess, setOnSuccess] = useState(false);

  return (
    <div style={{ position: "relative" }}>
      {onError && (
        <div
          style={{
            position: "absolute",
            display: "flex",
            justifyContent: "center",
            alignItems: "center",
            width: "300px",
            top: "-4vh",
            left: "calc(50% - 150px)",
            zIndex: 1,
          }}
        >
          <Alert severity="error"> Missing values </Alert>
        </div>
      )}
      {onSuccess && (
        <div
          style={{
            position: "absolute",
            display: "flex",
            justifyContent: "center",
            alignItems: "center",
            width: "300px",
            top: "-4vh",
            left: "calc(50% - 150px)",
            zIndex: 1,
          }}
        >
          <Alert severity="success"> Storage Server Updated </Alert>
        </div>
      )}
      <div
        style={{
          position: "absolute",
          display: "flex",
          justifyContent: "center",
          alignItems: "center",
          top: "0",
          left: "0",
          zIndex: 1,
          width: 80,
          height: 80,
        }}
      >
        <Tooltip title="Back" placement="right-start">
          <IconButton aria-label="back" onClick={handleGoBack}>
            <ArrowBackIcon fontSize="inherit" />
          </IconButton>
        </Tooltip>
      </div>
      <div
        style={{
          position: "absolute",
          display: "flex",
          justifyContent: "center",
          alignItems: "center",
          bottom: "40px",
          right: "40px",
          zIndex: 1,
        }}
      >
        <Button
          variant="contained"
          endIcon={<EditNoteIcon />}
          onClick={async () => {
            if (missingValues) {
              setOnError(true);
              setTimeout(() => {
                setOnError(false);
              }, 2000);
            } else {
              const res = await put("/storage-servers/update/" + id, {
                name: name,
                connection_config: connection,
                driver_config: driver,
              });

              if (res.status === 200) {
                setOnSuccess(true);
                setTimeout(() => {
                  setOnSuccess(false);
                  const searchParams = new URLSearchParams(
                    window.location.search
                  );
                  searchParams.delete("option");
                  window.history.replaceState(
                    {},
                    "",
                    `${window.location.pathname}?${searchParams}`
                  );
                  setRender(!render);
                }, 2000);
              }
            }
          }}
          disabled={missingValues}
        >
          Update
        </Button>
      </div>
      <TabSection
        tabs={[
          {
            missingValues: basicTabMissingValues,
            label: "Basic",
            component: (
              <StorageServerBasicForm
                name={name}
                setName={setName}
                setMissingValues={setBasicTabMissingValues}
              />
            ),
          },
          {
            missingValues: connectionTabMissingValues,
            label: "Connection",
            component: (
              <ConnectionForm
                connection={connection}
                setConnection={setConnection}
                setMissingValues={setConnectionTabMissingValues}
              />
            ),
          },
          {
            missingValues: driverTabMissingValues,
            label: "Driver",
            component: (
              <StorageServerDriverForm
                driver={driver}
                setDriver={setDriver}
                setMissingValues={setDriverTabMissingValues}
              />
            ),
          },
        ]}
      />
    </div>
  );
}

export default StorageServerEdit;
