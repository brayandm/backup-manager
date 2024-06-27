"use client";

import React from "react";
import Table from "@/components/Table";
import useSWR from "swr";
import { get, post } from "@/lib/backendApi";
import { CircularProgress, Fab, IconButton, Tooltip } from "@mui/material";
import AddIcon from "@mui/icons-material/Add";
import EditNoteIcon from "@mui/icons-material/EditNote";
import VisibilityIcon from "@mui/icons-material/Visibility";
import { Data, FilterType, HeadCell, Order } from "@/components/Table/Table";
import { formatBytes, formatDateToHumanReadable } from "@/utils/formatting";

enum BackupConfigurationStatus {
  ACTIVE = 0,
  INACTIVE = 1,
}
interface BackupConfigurationBackupsProps {
  render: boolean;
  setRender: React.Dispatch<React.SetStateAction<boolean>>;
}

const fetcher = (url: string) => get(url);

function BackupConfigurationBackups({
  render,
  setRender,
}: BackupConfigurationBackupsProps) {
  const [order, setOrder] = React.useState<Order>("asc");
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
    `/backup-configurations?page=${
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
      id: "total_backups",
      isOrderable: true,
      isFilterable: true,
      label: "Total Backups",
    },
    {
      id: "total_size_column",
      isOrderable: true,
      isFilterable: true,
      label: "Total Size",
    },
    {
      id: "last_backup_at_column",
      isOrderable: true,
      isFilterable: true,
      label: "Last Backup",
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
      id: "backups",
      isOrderable: false,
      isFilterable: false,
      label: "",
    },
    {
      id: "edit",
      isOrderable: false,
      isFilterable: false,
      label: "",
    },
  ];

  if (data) {
    data.data.data = data.data.data.map((d: any) => {
      return {
        ...d,
        total_size_column: formatBytes(d.total_size),
        last_backup_at_column:
          d.last_backup_at === null ? "No Backups" : d.last_backup_at,
        created_at_column: formatDateToHumanReadable(d.created_at),
        status_column: BackupConfigurationStatus[d.status],
        edit: (
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
            <Tooltip title="Edit" placement="right-start">
              <IconButton
                aria-label="edit"
                onClick={() => {
                  const url = new URL(window.location.href);
                  url.searchParams.set("option", "edit");
                  url.searchParams.set("id", d.id);
                  window.history.pushState({}, "", url);
                  setRender(!render);
                }}
              >
                <EditNoteIcon fontSize="inherit" />
              </IconButton>
            </Tooltip>
          </div>
        ),
        backups: (
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
            <Tooltip title="View Backups" placement="right-start">
              <IconButton
                aria-label="view"
                onClick={() => {
                  const url = new URL(window.location.href);
                  url.searchParams.set("option", "backups");
                  url.searchParams.set("id", d.id);
                  window.history.pushState({}, "", url);
                  setRender(!render);
                }}
              >
                <VisibilityIcon fontSize="inherit" />
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
      await post("/backup-configurations/delete-multiple", {
        ids: selected,
      });
    } else {
      await post("/backup-configurations/delete-all-except", {
        ids: selected,
      });
    }
    mutate();
    setSelected([]);
    setSelectedType("remove");
  };

  return data ? (
    <div
      style={{
        width: "86vw",
        display: "flex",
        flexDirection: "column",
        alignItems: "end",
      }}
    >
      <Table
        title="Backup Configurations"
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
        width: "86vw",
        height: "60vh",
        display: "flex",
        alignItems: "center",
        justifyContent: "center",
      }}
    >
      <CircularProgress />
    </div>
  ) : (
    <></>
  );
}

export default BackupConfigurationBackups;
