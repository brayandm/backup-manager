"use client";

import TabSection from "@/components/TabSection";
import ArrowBackIcon from "@mui/icons-material/ArrowBack";
import { Alert, Button, IconButton, Tooltip } from "@mui/material";
import AddIcon from "@mui/icons-material/Add";
import { useEffect, useState } from "react";
import ConnectionForm from "@/components/ConnectionForm";
import { get, post } from "@/lib/backendApi";
import BackupConfigurationBasicForm from "@/components/BackupConfigurationBasicForm";
import BackupConfigurationDriverForm from "@/components/BackupConfigurationDriverForm";
import BackupConfigurationScheduleForm from "@/components/BackupConfigurationScheduleForm";

interface BackupConfigurationCreateProps {
  render: boolean;
  setRender: React.Dispatch<React.SetStateAction<boolean>>;
}

function BackupConfigurationCreate({
  render,
  setRender,
}: BackupConfigurationCreateProps) {
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

  const [storageServerNames, setStorageServerNames] = useState<
    {
      id: number;
      name: string;
    }[]
  >([]);

  useEffect(() => {
    const fetchStorageServer = async () => {
      const res = await get("/storage-servers/names");
      if (res.status === 200) {
        const data = (await res) as {
          data: {
            id: number;
            name: string;
          }[];
        };

        setStorageServerNames(data.data);
      }
    };
    fetchStorageServer();
  }, []);

  const [name, setName] = useState("");
  const [storageServers, setStorageServers] = useState<
    {
      id: number;
      name: string;
    }[]
  >([]);
  const [connection, setConnection] = useState("[]");
  const [driver, setDriver] = useState("{}");
  const [scheduleCron, setScheduleCron] = useState("");
  const [retentionPolicy, setRetentionPolicy] = useState("{}");
  const [compression, setCompression] = useState("{}");
  const [encryption, setEncryption] = useState("{}");
  const [integrityCheck, setIntegrityCheck] = useState("{}");

  const [basicTabMissingValues, setBasicTabMissingValues] = useState(true);
  const [connectionTabMissingValues, setConnectionTabMissingValues] =
    useState(true);
  const [driverTabMissingValues, setDriverTabMissingValues] = useState(true);
  const [scheduleTabMissingValues, setScheduleTabMissingValues] =
    useState(true);
  const [advancedTabMissingValues, setAdvancedTabMissingValues] =
    useState(false);

  const missingValues =
    basicTabMissingValues ||
    connectionTabMissingValues ||
    driverTabMissingValues ||
    scheduleTabMissingValues ||
    advancedTabMissingValues;

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
          <Alert severity="success"> Backup Configuration Created </Alert>
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
          endIcon={<AddIcon />}
          onClick={async () => {
            if (missingValues) {
              setOnError(true);
              setTimeout(() => {
                setOnError(false);
              }, 2000);
            } else {
              const res = await post("/backup-configuration/store", {
                name: name,
                connection_config: connection,
                driver_config: driver,
              });

              if (res.status === 201) {
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
          Create
        </Button>
      </div>
      <TabSection
        tabs={[
          {
            missingValues: basicTabMissingValues,
            label: "Basic",
            component: (
              <BackupConfigurationBasicForm
                storageServerNames={storageServerNames}
                storageServers={storageServers}
                setStorageServers={setStorageServers}
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
              <BackupConfigurationDriverForm
                driver={driver}
                setDriver={setDriver}
                setMissingValues={setDriverTabMissingValues}
              />
            ),
          },
          {
            missingValues: scheduleTabMissingValues,
            label: "Schedule",
            component: (
              <BackupConfigurationScheduleForm
                scheduleCron={scheduleCron}
                setScheduleCron={setScheduleCron}
                setMissingValues={setScheduleTabMissingValues}
              />
            ),
          },
          {
            missingValues: advancedTabMissingValues,
            label: "Advanced",
            component: <div>Create 5</div>,
          },
        ]}
      />
    </div>
  );
}

export default BackupConfigurationCreate;
