"use client";

import React from "react";
import Table from "@/components/Table";
import useSWR from "swr";
import { get, post } from "@/lib/backendApi";
import ArrowBackIcon from "@mui/icons-material/ArrowBack";
import RestoreIcon from "@mui/icons-material/Restore";
import {
  Alert,
  CircularProgress,
  Fab,
  IconButton,
  Tooltip,
} from "@mui/material";
import AddIcon from "@mui/icons-material/Add";
import { Data, FilterType, HeadCell, Order } from "@/components/Table/Table";
import { formatBytes, formatDateToHumanReadable } from "@/utils/formatting";
import InProgress from "@/components/InProgress";

enum BackupStatus {
  CREATED = 0,
  RUNNING = 1,
  COMPLETED = 2,
  FAILED = 3,
}
interface BackupConfigurationBackupsProps {
  id: string;
  render: boolean;
  setRender: React.Dispatch<React.SetStateAction<boolean>>;
}

const fetcher = (url: string) => get(url);

function BackupConfigurationBackups({
  id,
  render,
  setRender,
}: BackupConfigurationBackupsProps) {
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

  const [order, setOrder] = React.useState<Order>("desc");
  const [orderBy, setOrderBy] = React.useState<keyof Data>("id");
  const [selected, setSelected] = React.useState<readonly number[]>([]);
  const [selectedType, setSelectedType] = React.useState<"remove" | "keep">(
    "remove"
  );
  const [filters, setFilters] = React.useState<
    Array<{ id: keyof Data; type: FilterType; value: string }>
  >([]);
  const [page, setPage] = React.useState(0);
  const [rowsPerPage, setRowsPerPage] = React.useState(5);

  const [onRestoring, setOnRestoring] = React.useState(false);
  const [onRestoringError, setOnRestoringError] = React.useState(false);
  const [onRestoringSuccess, setOnRestoringSuccess] = React.useState(false);

  let filterParams = "";

  filters.forEach((filter, index) => {
    filterParams += `&filters[${index}][key]=${encodeURIComponent(filter.id)}`;
    filterParams += `&filters[${index}][type]=${encodeURIComponent(
      filter.type
    )}`;
    filterParams += `&filters[${index}][value]=${encodeURIComponent(
      filter.value
    )}`;
  });

  const { data, error, isLoading, mutate } = useSWR(
    `/backup-configurations/backups/${id}?page=${
      page + 1
    }&pagination=${rowsPerPage}&sort_by=${encodeURIComponent(
      orderBy
    )}&sort_order=${order}${filterParams}`,
    fetcher
  );

  const columns: readonly HeadCell[] = [
    {
      id: "id",
      isOrderable: true,
      isFilterable: true,
      label: "ID",
    },
    {
      id: "name",
      isOrderable: true,
      isFilterable: true,
      label: "Name",
    },
    {
      id: "size_column",
      isOrderable: true,
      isFilterable: true,
      label: "Size",
    },
    {
      id: "created_at_column",
      isOrderable: true,
      isFilterable: true,
      label: "Created At",
    },
    {
      id: "status_column",
      isOrderable: true,
      isFilterable: true,
      label: "Status",
    },
    {
      id: "restore",
      isOrderable: false,
      isFilterable: false,
      label: "",
    },
  ];

  if (!isLoading && !error && data?.data) {
    data.data.data = data.data.data.map((d: any) => {
      return {
        ...d,
        size_column: d.size === null ? "Not Calculated" : formatBytes(d.size),
        created_at_column: formatDateToHumanReadable(d.created_at),
        status_column: BackupStatus[d.status],
        restore: (
          <div
            style={{
              display: "flex",
              justifyContent: "center",
              alignItems: "center",
            }}
            onClick={(event) => {
              event.stopPropagation();
            }}
          >
            <Tooltip title="Restore" placement="right-start">
              <IconButton
                aria-label="edit"
                onClick={() => {
                  setOnRestoring(true);
                  post("/backups/restore/" + d.id, {}).then((res) => {
                    if (res.status === 200) {
                      setOnRestoringSuccess(true);
                    } else {
                      setOnRestoringError(true);
                    }
                    setTimeout(() => {
                      setOnRestoring(false);
                      setOnRestoringError(false);
                      setOnRestoringSuccess(false);
                    }, 2000);
                  });
                }}
              >
                <RestoreIcon fontSize="inherit" />
              </IconButton>
            </Tooltip>
          </div>
        ),
      };
    });
  }

  const onDeleted = async (
    selected: readonly number[],
    selectedType: "remove" | "keep"
  ) => {
    if (selectedType === "remove") {
      await post("/backups/delete-multiple", {
        ids: selected,
      });
    } else {
      await post("/backups/delete-all-except?backup_configuration_id=" + id, {
        ids: selected,
      });
    }
    mutate();
    setSelected([]);
    setSelectedType("remove");
  };

  return (
    <>
      {onRestoring && (
        <InProgress
          title="Restoring Backup"
          success={onRestoringSuccess}
          error={onRestoringError}
        />
      )}
      {!isLoading && !error && data?.data ? (
        <div
          style={{
            width: "84vw",
            display: "flex",
            flexDirection: "column",
            alignItems: "end",
          }}
        >
          <Table
            title={
              <div
                style={{
                  display: "flex",
                  flexDirection: "row",
                  alignItems: "center",
                }}
              >
                <div
                  style={{
                    display: "flex",
                    justifyContent: "center",
                    alignItems: "center",
                    top: "0",
                    left: "0",
                    zIndex: 1,
                    width: 50,
                    height: 50,
                  }}
                >
                  <Tooltip title="Back" placement="right-start">
                    <IconButton aria-label="back" onClick={handleGoBack}>
                      <ArrowBackIcon fontSize="inherit" />
                    </IconButton>
                  </Tooltip>
                </div>
                Backups
              </div>
            }
            columns={columns}
            rows={data.data.data as Data[]}
            count={data.data.total}
            order={order}
            setOrder={setOrder}
            orderBy={orderBy}
            setOrderBy={setOrderBy}
            selected={selected}
            setSelected={setSelected}
            selectedType={selectedType}
            setSelectedType={setSelectedType}
            filters={filters}
            setFilters={setFilters}
            page={page}
            setPage={setPage}
            rowsPerPage={rowsPerPage}
            setRowsPerPage={setRowsPerPage}
            onDeleted={onDeleted}
          />
          <div
            style={{
              margin: "10px",
            }}
          >
            <Tooltip title="Add">
              <Fab
                color="primary"
                aria-label="add"
                onClick={() => {
                  const url = new URL(window.location.href);
                  url.searchParams.set("option", "create");
                  window.history.pushState({}, "", url);
                  setRender(!render);
                }}
              >
                <AddIcon />
              </Fab>
            </Tooltip>
          </div>
        </div>
      ) : isLoading ? (
        <div
          style={{
            width: "84vw",
            height: "60vh",
            display: "flex",
            alignItems: "center",
            justifyContent: "center",
          }}
        >
          <CircularProgress />
        </div>
      ) : (
        <div
          style={{
            position: "fixed",
            display: "flex",
            justifyContent: "center",
            alignItems: "center",
            width: "300px",
            top: "10%",
            left: "50%",
            zIndex: 1,
          }}
        >
          <Alert severity="error"> Error fetching data </Alert>
        </div>
      )}
    </>
  );
}

export default BackupConfigurationBackups;
